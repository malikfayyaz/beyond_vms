<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunity;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\Consultant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Activitylog\Models\Activity;


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
                    return $row->workOrder && $row->workOrder->date_range
                        ? $row->workOrder->date_range
                        : 'N/A';
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
                    $btn = ' <a href="' . route('admin.workorder.show', $row->id) . '"
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
        return view('admin.workorder.index', compact('counts')); // Assumes you have a corresponding Blade view
    }    //
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
            
            $log->properties = array_merge($log->properties->toArray(), ['attributes' => $attributes]); // Update properties
            // dd($log->properties['attributes']);
        }
        $workorder = CareerOpportunitiesWorkorder::findOrFail($id);
        $rejectReasons =  Setting::where('category_id', 27)->get();        
        return view('admin.workorder.view', compact('workorder','rejectReasons','logs'));
    }
public function withdrawWorkorder(Request $request)
{
    $workorder = CareerOpportunitiesWorkorder::findOrFail($request->workorder_id);
    $vendorSubmission = CareerOpportunitySubmission::findOrFail($workorder->submission_id);
    if ($vendorSubmission) {
        $vendorSubmission->rejected_type = 1;
        $vendorSubmission->rejected_by = Admin::getAdminIdByUserId(Auth::id());
        $vendorSubmission->resume_status = 12;
        $vendorSubmission->note_for_rejection = $request->note;
        $vendorSubmission->notes = $request->note;
        $vendorSubmission->reason_for_rejection = $request->reason;
        $vendorSubmission->date_rejected = now();        
        $vendorSubmission->Update(); // Save the changes
    }
    $offer = CareerOpportunitiesOffer::where('id', $workorder->offer_id)->first();                
    if ($offer) {
        $offer->status = 13;
        $offer->withdraw_reason = $request->reason;
        $offer->notes = $request->note;
        $offer->modified_by_id = Admin::getAdminIdByUserId(Auth::id());
        $offer->reason_rejection = $request->reason;
        $offer->date_modified = now();
        $offer->offer_rejection_date = now();
        $offer->modified_by_type = 1;
        $offer->Update();
    }
    $workorder = CareerOpportunitiesWorkorder::where('submission_id', $workorder->submission_id)->first();
    if ($workorder) {
        $workorder->status = 6;
        $workorder->rejection_date = now();
        $workorder->modified_by_id = Admin::getAdminIdByUserId(Auth::id());
        $workorder->modified_by_type = '1';
        $workorder->reason_rejection = $request->reason;
        $workorder->rejection_notes = $request->note;
        $workorder->Update();
    }
    session()->flash('success', 'Workorder/Submission Withdrawn successfully!');
    return response()->json([
        'success' => true,
        'message' => 'Workorder/Submission Withdrawn successfully!',
        'redirect_url' => route('admin.workorder.index'),
    ]);
}
}
