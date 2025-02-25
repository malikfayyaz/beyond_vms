<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\OfferWorkFlow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitySubmission;
use App\Facades\CareerOpportunitiesOffer as offerHelper;
use App\Facades\Rateshelper as Rateshelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Activitylog\Models\Activity;
use App\Models\Consultant;
use App\Models\Vendor;
use App\Models\FormBuilder;


class CareerOpportunitiesOfferController extends Controller
{
    // Display a listing of career opportunities
    public function index(Request $request)
    {
        $counts = [
            'all_offers' => CareerOpportunitiesOffer::count(),
            'draft' => CareerOpportunitiesOffer::where('status', 0)->count(),
            'pending' => CareerOpportunitiesOffer::where('status', 1)->count(),
            'rejected' => CareerOpportunitiesOffer::where('status', 2)->count(),
            'approved' => CareerOpportunitiesOffer::where('status', 3)->count(),
            'waiting_for_supplier_approval' => CareerOpportunitiesOffer::where('status', 4)->count(),
            'withdrawn' => CareerOpportunitiesOffer::where('status', 13)->count(),
        ];

        if ($request->ajax()) {
            $offers = CareerOpportunitiesOffer::with(['consultant','careerOpportunity','hiringManager','vendor']);
            $currentId = $request->input('currentId');
            $subId = $request->input('subId');
            if ($request->has('type')) {
                $type = $request->input('type');
                switch ($type) {
                    case 'all_offers':
                        break;
                    case 'pending':
                        $offers->where('status', 1);
                        break;
                    case 'approved':
                        $offers->where('status', 3);
                        break;
                    case 'rejected':
                        $offers->where('status', 2);
                        break;


                    // Add additional cases as needed
                    default:
                        break; // Show all submissions if no type is specified
                }
            }

            if ($currentId && $subId) {
                $offers->where('submission_id', $subId)
                       ->where('id', '!=', $currentId);
            }

            return DataTables::of($offers)
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->careerOpportunity->id . '">' . $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' . '</span>';
                })
                ->addColumn('hiring_manger', function($row) {
                    return $row->careerOpportunity->hiringManager ? $row->careerOpportunity->hiringManager->fullname : 'N/A';
                })
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('status', function($row) {
                    return CareerOpportunitiesOffer::getOfferStatus($row->status);
                })
                ->addColumn('created_at', function($row) {
                    return $row->created_at ? formatDateTime($row->created_at) : 'N/A';
                })
                ->addColumn('wo_status', function($row) {
                    return  '';
                })->addColumn('worker_type', function($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('client.offer.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->rawColumns(['career_opportunity','action'])
                ->make(true);
            }
        return view('client.offer.index', compact('counts'));
    }

    // Show the form for creating a new career opportunity offer
    public function create($id)
    {
        $formBuilderData = FormBuilder::where('type', 3)
        ->where('status', 'active')
        ->first();

        $submission =  CareerOpportunitySubmission::findOrFail($id);

        return view('client.offer.create',[
            'submission'=>$submission,
            'formBuilderData'=>$formBuilderData
        ]);
    }

    public function store(Request $request)
    {
        $dynamicRules = [];

        foreach ($request->all() as $key => $value) {
            if (preg_match('/^text-/', $key)) {
                $dynamicRules[$key] = 'nullable|string'; // Rule for text inputs
            } elseif (preg_match('/^textarea-/', $key)) {
                $dynamicRules[$key] = 'nullable|string'; // Rule for textarea inputs
            } elseif (preg_match('/^checkbox-/', $key)) {
                $dynamicRules[$key] = 'nullable|array'; // Validate as an array
                $dynamicRules["{$key}.*"] = 'in:true,false'; // Rule for checkboxes
            } elseif (preg_match('/^radio-/', $key)) {
                $dynamicRules[$key] = 'nullable|string'; // Rule for radio buttons
            } elseif (preg_match('/^select-/', $key)) {
                $dynamicRules[$key] = 'nullable|string'; // Rule for select dropdowns
            } elseif (preg_match('/^file-/', $key)) {
                $dynamicRules[$key] = 'nullable|file'; // Rule for file uploads
            } elseif (preg_match('/^number-/', $key)) {
                $dynamicRules[$key] = 'nullable|numeric'; // Rule for number inputs
            } elseif (preg_match('/^date-/', $key)) {
                $dynamicRules[$key] = 'nullable|date_format:m/d/Y'; // Rule for date inputs
            } elseif (preg_match('/^email-/', $key)) {
                $dynamicRules[$key] = 'nullable|email'; // Rule for email inputs
            }
        }

        $validatednewData = $request->validate($dynamicRules);

         // Define your validation rules
         $rules = [
            'startDate' => 'required|date_format:m/d/Y',
            'endDate' => 'required|date_format:m/d/Y',
            'approvingManager' => 'required|integer',
            'markup' => 'required',
            'submissionid' => 'required|integer',
            'location' => 'required|integer',
            'remote' => 'required|in:Yes,No',
            'payRate' => 'required|numeric|min:0',
            'billRate' => 'required|numeric|min:0',
            'overTime' => 'nullable',
            'doubleRate' => 'nullable',
            'overTimeCandidate' => 'nullable',
            'doubleTimeCandidate' => 'nullable',

        ];

        $messages = [

            // Add more custom messages as needed
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        // dd($validator );
        // If validation fails, return JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity status
        }
        $validatedData = $validator->validated();
        $existingOffer = CareerOpportunitiesOffer::where('submission_id', $request->submissionid)
        ->whereIn('status', [4, 1])
        ->first();
        if($existingOffer){

            session()->flash('success', 'Offer already exist!');
            return response()->json([
                'success' => true,
                'message' => 'Offer already exist!',
                'redirect_url' => route('client.offer.show',  ['id' => $request->submissionid]) // Redirect back URL for AJAX
            ]);
         }
         $submission = CareerOpportunitySubmission::findOrFail($request->submissionid);
         $jobData = $submission->careerOpportunity;
         $mapedData = [
            "submission_id" =>$validatedData['submissionid'],
            "vendor_id" =>$submission->vendor_id,
            "candidate_id" =>$submission->candidate_id,
            "hiring_manager_id" =>$validatedData['approvingManager'],
            "career_opportunity_id" =>$submission->career_opportunity_id,
            "location_id" =>$validatedData['location'],
            "markup" =>$validatedData['markup'],
            "created_by_id" =>Client::getClientIdByUserId(Auth::id()),
            "created_by_type" =>2,
            "status" =>1,
            "offer_pay_rate" =>removeComma($validatedData['payRate']),
            "offer_bill_rate" =>removeComma($validatedData['billRate']),
            "over_time" =>removeComma($validatedData['overTime']),
            "client_overtime" =>removeComma($validatedData['overTimeCandidate']),
            "double_time" =>removeComma($validatedData['doubleRate']),
            "client_doubletime" =>removeComma($validatedData['doubleTimeCandidate']),

            "remote_option" =>$validatedData['remote'],
            // "notes" =>$validatedData['notes'],
            "start_date" =>!empty($validatedData['startDate'])
            ? Carbon::createFromFormat('m/d/Y', $validatedData['startDate'])->format('Y-m-d')  : null,
            "end_date" =>!empty($validatedData['endDate'])
            ? Carbon::createFromFormat('m/d/Y', $validatedData['endDate'])->format('Y-m-d')  : null,
         ];
         $offerCreate = CareerOpportunitiesOffer::create( $mapedData );
         $offerCreate->offer_details = $validatednewData; // Save the validated data as JSON
         $offerCreate->save();
         Rateshelper::calculateVendorRates($offerCreate,$offerCreate->offer_bill_rate,$offerCreate->client_overtime,$offerCreate->client_doubletime);
         Rateshelper::calculateOfferEstimates($offerCreate,$jobData);
         offerHelper::createOfferWorkflow($offerCreate);

         session()->flash('success', 'Offer saved successfully!');
         return response()->json([
             'success' => true,
             'message' => 'Offer saved successfully!',
             'redirect_url' => route('client.offer.index') // Redirect back URL for AJAX
         ]);

    }

    // Show a specific career opportunity offer
    public function show($id)
    {
        $logs = Activity::where('subject_id', $id)->where('log_name', 'offer')->latest()->get();
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
            $status_name = CareerOpportunitiesOffer::getOfferStatus($statusID);
            if ($status_name) {
                $attributes['status_name'] = $status_name;
            }
            $log->properties = array_merge($log->properties->toArray(), ['attributes' => $attributes]); // Update properties

        }

        $workflows = OfferWorkFlow::where('offer_id', $id)->get();
        $offer = CareerOpportunitiesOffer::findOrFail($id);

        $formBuilder = FormBuilder::where('type', 3)->first();

        $formFields = [];
        if ($formBuilder) {
            $formFields = json_decode($formBuilder->data, true);
        }

        return view('client.offer.view', compact('offer','workflows', 'logs', 'formFields'));
    }
    public function offerworkflowAccept(Request $request)
    {
        $actionType = $request->input('actionType');
        $validated = $request->validate([
            'rowId' => 'required|integer',
        ]);
        $workflow = OfferWorkFlow::findOrFail($request->rowId);
        if ($actionType == 'Accept') {
            offerHelper::approveofferWorkFlow($request);
            $message = 'Offer Workflow Accepted successfully!';
            session()->flash('success', $message);
        } elseif ($actionType == 'Reject') {
            offerHelper::rejectoffersWorkFlow($request);
            $message = 'Offer Workflow Rejected successfully!';
            session()->flash('success', $message);
        }
        return response()->json([
            'success' => true,
            'message' => $message,
            'redirect_url' => route('client.offer.show', ['id' => $workflow->offer_id]),
        ]);


    }
}
