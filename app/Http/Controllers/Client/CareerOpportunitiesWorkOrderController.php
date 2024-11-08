<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesWorkorder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesWorkOrderController extends Controller
{
    public function index(Request $request)
    {
        $counts = [
            'all_wo' => CareerOpportunitiesWorkorder::count(),
            'pending' => CareerOpportunitiesWorkorder::where('status', 0)->count(),
            'approved' => CareerOpportunitiesWorkorder::where('status', 1)->count(),
            'rejected' => CareerOpportunitiesWorkorder::where('status', 2)->count(),
            'closed' => CareerOpportunitiesWorkorder::where('status', 3)->count(),
            'expired' => CareerOpportunitiesWorkorder::where('status', 4)->count(),
            'rehire' => CareerOpportunitiesWorkorder::where('status', 5)->count(),
            'withdrawn' => CareerOpportunitiesWorkorder::where('status', 6)->count(),
            'pending_approval' => CareerOpportunitiesWorkorder::where('status', 7)->count(),
            'cancelled' => CareerOpportunitiesWorkorder::where('status', 14)->count(),
        ];

        if ($request->ajax()) {
            
            $data = CareerOpportunitiesWorkorder::with('hiringManager','vendor','careerOpportunity');
            
            if ($request->has('type')) {
                $type = $request->input('type');
                switch ($type) {
                    case 'all_wo':
                        break;
                    case 'pending':
                        $data->where('status', 0);
                        break;
                    case 'approved':
                        $data->where('status', 1);
                        break;
                    case 'rejected':
                        $data->where('status', 2);
                        break;
                    case 'closed':
                        $data->where('status', 3);
                        break;
                    case 'expired':
                        $data->where('status', 4);
                        break;
                    case 'rehire':
                        $data->where('status', 5);
                        break;
                    case 'withdrawn':
                        $data->where('status', 6);
                        break;
                    case 'pending_approval':
                        $data->where('status', 7);
                        break;
                    

                    // Add additional cases as needed
                    default:
                        break; // Show all submissions if no type is specified
                }
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function ($row) {
                    return $row->hiringManager->full_name ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->careerOpportunity->id . '">' . $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' . '</span>';
                })
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('status', function($row) {
                    return CareerOpportunitiesWorkorder::getWorkorderStatus($row->status);
                })
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('duration', function ($row) {
                    return $row->date_range ? $row->date_range : 'N/A';
                })
                ->addColumn('submissions', function ($row) {
                    return $row->submissions_count;
                })
                ->addColumn('worker_type', function($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('client.workorder.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>';

                    return $btn;
                })
                ->rawColumns(['career_opportunity','action'])
                ->make(true);
        }

        // Logic to get and display catalog items
        return view('client.workorder.index', compact('counts')); // Assumes you have a corresponding Blade view
    }
    public function show($id)
    {
        $workorder = CareerOpportunitiesWorkorder::findOrFail($id);
        return view('client.workorder.view', compact('workorder'));
    }
}
