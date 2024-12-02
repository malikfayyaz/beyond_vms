<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunity;
use App\Models\Vendor;
use App\Models\VendorJobRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $vendorid = Vendor::getVendorIdByUserId(\Auth::id());
$counts = [
    'all_jobs' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->count(),

    'open' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 3)->count(),

    'filled' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 4)->count(),

    'new' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 11)->count(),

    'closed' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 12)->count(),

    'pending' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 1)->count(),

    'sourcing' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 13)->count(),

    'pendingpmo' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 22)->count(),

    'open_pending_release' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->whereIn('jobStatus', [3, 23])->count(),

    'pending_hm' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->whereIn('jobStatus', [1, 23, 24])->count(),

    'quick_create' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->whereIn('jobStatus', [1, 3, 13])->count(),

    'draft' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->where('jobStatus', 2)->count(),

    'active' => CareerOpportunity::whereHas('VendorJobRelease', function ($query) use ($vendorid) {
                        $query->where('user_id', $vendorid);
                    })->whereIn('jobStatus', [1, 3, 6, 13, 23, 24])->count(),
];

    if ($request->ajax()) {
        // Start query
        $data = CareerOpportunity::with('hiringManager', 'workerType')
            ->withCount('submissions')
            ->orWhereHas('VendorJobRelease', function ($query) use ($vendorid) {
                $query->where('user_id', $vendorid);
            })->orderby('id', 'desc');

        // Apply filtering based on request 'type'
        if ($request->has('type')) {
            $type = $request->input('type');
            switch ($type) {
                case "All_jobs":
                    // No additional filtering
                    break;
                case "open":
                    $data->where('jobStatus', 3);
                    break;
                case "filled":
                    $data->where('jobStatus', 4);
                    break;
                case "New":
                    $data->where('jobStatus', 11);
                    break;
                case "closed":
                    $data->where('jobStatus', 12);
                    break;
                case "Pending":
                    $data->where('jobStatus', 1);
                    break;
                case "sourcing":
                    $data->where('jobStatus', 13);
                    break;
                case "pendingpmo":
                    $data->where('jobStatus', 22);
                    break;
                case "open-pending-release":
                    $data->whereIn('jobStatus', [3, 23]);
                    break;
                case "pending-hm":
                    $data->whereIn('jobStatus', [1, 23, 24]);
                    break;
                case "Quickcreate":
                    $data->whereIn('jobStatus', [1, 3, 13]);
                    break;
                case "draft":
                    $data->where('jobStatus', 2);
                    break;
                case 'active':
                    $data->whereIn('jobStatus', [1, 3, 6, 13, 23, 24]);
                    break;
                default:
                    break;
            }
        }

        // Return the data using DataTables
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('id', function ($row) {
                return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->id . '</span>';
            })
            ->addColumn('title', function ($row) {
                return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->title . '</span>';
            })
            ->addColumn('jobStatus', function ($row) {
                return (isset($row->jobStatus)) ? $row->getStatus($row->jobStatus) : 'N/A';
            })
            ->addColumn('hiring_manager', function ($row) {
                return $row->hiringManager->full_name ? $row->hiringManager->full_name : 'N/A';
            })
            ->addColumn('duration', function ($row) {
                return $row->date_range ? $row->date_range : 'N/A';
            })
            ->addColumn('submissions', function ($row) {
                return $row->submissions_count;
            })
            ->addColumn('worker_type', function ($row) {
                return $row->workerType ? $row->workerType->title : 'N/A';
            })
            ->addColumn('action', function ($row) {
                $btn = ' <a href="' . route('vendor.career-opportunities.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                       <i class="fas fa-eye"></i>
                     </a>';
                return $btn;
            })
            ->rawColumns(['action', 'id', 'title'])
            ->make(true);
    }

    // Logic to get and display catalog items
    return view('vendor.career_opportunities.index', compact('counts')); // Assumes you have a corresponding Blade view
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = CareerOpportunity::with('hiringManager')->findOrFail($id);
       // dd($job);
        // Optionally, you can dump the data for debugging purposes
        // dd($job); // Uncomment to check the data structure

        // Return the view and pass the job data to it
        return view('vendor.career_opportunities.view', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
