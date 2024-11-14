<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesOffer;
use Illuminate\Support\Facades\Validator;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\Vendor;
use App\Models\WorkorderBackground;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use App\Models\Admin;
use App\Models\Client;
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
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('status', function($row) {
                    return CareerOpportunitiesWorkorder::getWorkorderStatus($row->status);
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
                    $btn = ' <a href="' . route('vendor.workorder.show', $row->id) . '"
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
        return view('vendor.workorder.index', compact('counts')); // Assumes you have a corresponding Blade view
    }

    public function store(Request $request)
    {

         // Convert "true"/"false" strings to boolean for the relevant fields
        $request->merge([
            'codeOfConduct' => filter_var($request->codeOfConduct, FILTER_VALIDATE_BOOLEAN),
            'dataPrivacy' => filter_var($request->dataPrivacy, FILTER_VALIDATE_BOOLEAN),
            'nonDisclosure' => filter_var($request->nonDisclosure, FILTER_VALIDATE_BOOLEAN),
            'criminalBackground' => filter_var($request->criminalBackground, FILTER_VALIDATE_BOOLEAN),
        ]);
        // Define your validation rules
        $rules = [
            'codeOfConduct' => 'required|boolean',
            'dataPrivacy' => 'required|boolean',
            'nonDisclosure' => 'required|boolean',
            'criminalBackground' => 'required|boolean',
            'accountManager' => 'required|integer',
            'recruitmentManager' => 'required|integer',
            'workorder_id'=> 'required|integer',
            'fileUpload' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ];

        $messages = [
            'codeOfConduct.required' => 'You must agree to the Code of Conduct.',
            'dataPrivacy.required' => 'You must agree to the Data Privacy Policy.',
            'nonDisclosure.required' => 'You must agree to the Non-Disclosure Agreement.',
            'criminalBackground.required' => 'You must agree to the Criminal Background Check.',
            'accountManager.required' => 'An Account Manager is required.',
            'recruitmentManager.required' => 'A Recruitment Manager is required.',
            'fileUpload.mimes' => 'Only PDF and DOC  files are allowed.',
            'fileUpload.max' => 'The file size must not exceed 2MB.',
            // Add more custom messages as needed
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        // dd(removeComma($request->locationTax) );
        // If validation fails, return JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity status
        }
        $validatedData = $validator->validated();
        $workorder = CareerOpportunitiesWorkorder::findOrFail($request->workorder_id);

        $submission = $workorder->submission;
        if(isset($submission)) {
            $submission->emp_msp_account_mngr = $request->recruitmentManager;
			$submission->save();
        }
        $workorder->location_tax = removeComma($request->locationTax);
        $workorder->save();
        $filename = handleFileUpload($request, 'fileUpload', 'background_verify');
        if(empty($workorder->workorderbackground)){
            $bgVerfication = [
                'code_of_conduct' => $validatedData['codeOfConduct'],
                'data_privacy' => $validatedData['dataPrivacy'],
                'non_disclosure' => $validatedData['nonDisclosure'],
                'criminal_background' => $validatedData['criminalBackground'],
                'workorder_id' => $workorder->id,
                'status' => 1,
                'file'=>$filename,
                'markcompleted_date' => now(),
                'markcompleted_by' =>Vendor::getVendorIdByUserId(\Auth::id()),
            ];
            WorkorderBackground::create($bgVerfication);
        } else {
            if($filename == null || empty($filename)) {
                $filename =  $workorder->workorderbackground->file;
            }
            $bgVerfication = [
                'code_of_conduct' => $validatedData['codeOfConduct'],
                'data_privacy' => $validatedData['dataPrivacy'],
                'non_disclosure' => $validatedData['nonDisclosure'],
                'file'=>$filename,
                'criminal_background' => $validatedData['criminalBackground'],

            ];
            $workorder->workorderbackground->update($bgVerfication);
        }

        if($request->type == "saveAndSubmit") {
                $workorder->verification_status=1;
	        	$workorder->vendor_bg_date = now();
	        	$workorder->verification_status_vendor=1;
	        	$workorder->bg_reviewed_msp=1;
				$workorder->markcompleted_by = Vendor::getVendorIdByUserId(\Auth::id());
				$workorder->markcompleted_date = now();
                $workorder->save();
        }


        session()->flash('success', 'Onboarding Document Background Screening submitted successfully');
        return response()->json([
            'success' => true,
            'message' => 'Onboarding Document Background Screening submitted successfully',
            'redirect_url' => route('vendor.workorder.show', ['id' => $workorder->id])// Redirect back URL for AJAX
        ]);

    }
    public function show($id)
    {$logs = Activity::where('subject_id', $id)->where('log_name', 'workorder')->latest()->get();
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
        return view('vendor.workorder.view', compact('workorder', 'logs'));
    }

    public function destroy($id)
    {
        $background = WorkOrderBackground::findOrFail($id);

        if ($background->file && Storage::exists('public/background_verify/' . $background->file)) {
            // Delete file from storage
            Storage::delete('public/background_verify/' . $background->file);
        }

        // Delete record from database
        // $background->delete();
        $background->file = null;
        $background->save();
        return response()->json(['message' => 'File deleted successfully','redirect_url' => route('vendor.workorder.show', ['id' => $background->workorder_id])]);
    }
}
