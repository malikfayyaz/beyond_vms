<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesWorkorder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Activitylog\Models\Activity;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\Consultant;
use Illuminate\Support\Carbon;


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
        $logs = Activity::where('subject_id', $id)->where('log_name', 'workorder')->latest()->get();
        $candidateIds = $logs->pluck('properties.attributes.candidate_id')->unique();
        $hiringManagerIds = $logs->pluck('properties.attributes.hiring_manager_id')->unique();
        $vendorIds = $logs->pluck('properties.attributes.vendor_id')->unique();
    
        // Load all relevant consultants, clients, and vendors
        $candidates = Consultant::whereIn('id', $candidateIds)->get()->keyBy('id');
        $hiringManagers = Client::whereIn('id', $hiringManagerIds)->get()->keyBy('id');
        $vendors = Vendor::whereIn('id', $vendorIds)->get()->keyBy('id');

        foreach ($logs as $log)
        {
           $attributes = $log->properties['attributes'];

            // Candidate Name
            $candidateId = $attributes['candidate_id'];
            if (isset($candidates[$candidateId])) {
                $attributes['candidate_name'] = $candidates[$candidateId]->full_name;
            }

            // Hiring Manager Name
            $hiring_mID = $attributes['hiring_manager_id'];
            if (isset($hiringManagers[$hiring_mID])) {
                $attributes['hiring_manager_name'] = $hiringManagers[$hiring_mID]->full_name;
            }

            // Vendor Name
            $vendorID = $attributes['vendor_id'];
            if (isset($vendors[$vendorID])) {
                $attributes['vendor_name'] = $vendors[$vendorID]->full_name;
            }

            // Status Name
            $statusID = $attributes['status'];
            $status_name = CareerOpportunitiesWorkorder::getWorkorderStatus($statusID);
            if ($status_name) {
                $attributes['status_name'] = $status_name;
            }

            // Job Type Title
            $jobTypeID = $attributes['job_type'];
            $jobType = CareerOpportunitiesWorkorder::find($jobTypeID)?->jobType;
            if ($jobType) {
                $attributes['job_type_title'] = $jobType->title;
            }

            if (isset($attributes['start_date']) && $attributes['start_date']) {
                $attributes['start_date_formatted'] = Carbon::parse($attributes['start_date'])->format('m/d/Y');
            }
            if (isset($attributes['end_date']) && $attributes['end_date']) {
                $attributes['end_date_formatted'] = Carbon::parse($attributes['end_date'])->format('m/d/Y');
            }
            
            $log->properties = array_merge($log->properties->toArray(), ['attributes' => $attributes]); 
        }
        $workorder = CareerOpportunitiesWorkorder::findOrFail($id);
        return view('client.workorder.view', compact('workorder', 'logs'));
    }
}
