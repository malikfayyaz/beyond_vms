<?php
namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\CareerOpportunitiesWorkflow;
use App\Models\OfferWorkFlow;
use App\Models\Consultant;
use App\Models\Client;
use App\Models\Vendor;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitySubmission;
use App\Facades\CareerOpportunitiesOffer as offerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Facades\Rateshelper as Rateshelper;
use Spatie\Activitylog\Models\Activity;


class CareerOpportunitiesOfferController extends BaseController
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
                ->addColumn('status', function($row) {
                    return CareerOpportunitiesOffer::getOfferStatus($row->status);
                })
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('created_at', function($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d') : 'N/A';
                })
                ->addColumn('wo_status', function($row) {
                    return  '';
                })->addColumn('worker_type', function($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('admin.offer.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->rawColumns(['career_opportunity','action'])
                ->make(true);
            }
        return view('admin.offer.index' , compact('counts'));
    }

    // Show the form for creating a new career opportunity offer
    public function create($id)
    {
       $submission =  CareerOpportunitySubmission::findOrFail($id);
       return view('admin.offer.create',[
        'submission'=>$submission
         ]);
    }

    // Store a newly created career opportunity offer in the database
    public function store(Request $request)
    {
         // Define your validation rules
         $rules = [
            'startDate' => 'required|date_format:Y/m/d',
            'endDate' => 'required|date_format:Y/m/d',
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
                'redirect_url' => route('admin.offer.create',  ['id' => $request->submissionid]) // Redirect back URL for AJAX
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
            "created_by_id" =>Admin::getAdminIdByUserId(Auth::id()),
            "created_by_type" =>1,
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
            ? Carbon::createFromFormat('Y/m/d', $validatedData['startDate'])->format('Y-m-d')  : null,
            "end_date" =>!empty($validatedData['endDate'])
            ? Carbon::createFromFormat('Y/m/d', $validatedData['endDate'])->format('Y-m-d')  : null,
         ];
         $offerCreate = CareerOpportunitiesOffer::create( $mapedData );
         Rateshelper::calculateVendorRates($offerCreate,$offerCreate->offer_bill_rate,$offerCreate->client_overtime,$offerCreate->client_doubletime);
         Rateshelper::calculateOfferEstimates($offerCreate,$jobData);
         offerHelper::createOfferWorkflow($offerCreate);
        session()->flash('success', 'Offer saved successfully!');
         return response()->json([
             'success' => true,
             'message' => 'Offer saved successfully!',
             'redirect_url' => route('admin.offer.index') // Redirect back URL for AJAX
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
        $rejectionreason = checksetting(17);
        $workflows = OfferWorkFlow::where('offer_id', $id)->get();
        $offer = CareerOpportunitiesOffer::findOrFail($id);
        return view('admin.offer.view', compact('offer', 'workflows', 'rejectionreason', 'logs'));
    }

    // Show the form for editing an existing career opportunity offer
    public function edit($id)
    {
        $offer = CareerOpportunitiesOffer::findOrFail($id);
        return view('admin.offer.edit', compact('offer'));
    }

    // Update the specified career opportunity offer in the database
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'job_title' => 'required|string|max:255',
            'job_description' => 'nullable|string',
            'location' => 'required|string',
            'salary' => 'nullable|numeric',
            'employment_type' => 'required|string',
            'company_id' => 'required|exists:companies,id',
        ]);

        $offer = CareerOpportunitiesOffer::findOrFail($id);
        $offer->update($validatedData);

        return redirect()->route('admin.offer.index')->with('success', 'Career opportunity offer updated successfully.');
    }

    // Remove the specified career opportunity offer from the database
    public function destroy($id)
    {
        $offer = CareerOpportunitiesOffer::findOrFail($id);
        $offer->delete();

        return redirect()->route('admin.offer.index')->with('success', 'Career opportunity offer deleted successfully.');
    }

   
    public function numberFormat($data)
    {
        return number_format($data, 2);
    }
    public function offerworkflowAccept(Request $request)
    {
        $actionType = $request->input('actionType');
        $validated = $request->validate([
            'rowId' => 'required|integer',
            'reason' => 'required_if:actionType,Reject|integer',
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
            'redirect_url' => route('admin.offer.show', ['id' => $workflow->offer_id]),
        ]);


    }

}
