<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\OfferWorkFlow;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesWorkorder;
use App\Facades\CareerOpportunitiesOffer as offerHelper;
use App\Facades\Rateshelper as Rateshelper;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesOfferController extends Controller
{
    // Display a listing of career opportunities
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $offers = CareerOpportunitiesOffer::with(['consultant','careerOpportunity','hiringManager','vendor'])->get();
            return DataTables::of($offers)
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function($row) {
                    return $row->careerOpportunity ? $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' : 'N/A';
                })
                ->addColumn('hiring_manger', function($row) {
                    return $row->hiringManager ? $row->hiringManager->fullname : 'N/A';
                })
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('status', function($row) {
                    return CareerOpportunitiesOffer::getOfferStatus($row->status);
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
                    return '<a href="' . route('vendor.offer.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->make(true);
            }
        return view('vendor.offer.index');
    }

    // Show the form for creating a new career opportunity offer
    public function create($id)
    {
       $submission =  CareerOpportunitySubmission::findOrFail($id);
       return view('vendor.offer.create',[
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
        // if($existingOffer){

        //     session()->flash('success', 'Offer already exist!');
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Offer already exist!',
        //         'redirect_url' => route('vendor.offer.show',  ['id' => $request->submissionid]) // Redirect back URL for AJAX
        //     ]);
        // }
        $submission = CareerOpportunitySubmission::findOrFail($request->submissionid);
        $jobData = $submission->careerOpportunity;
        $vendorId = Vendor::getVendorIdByUserId(\Auth::id());
        $mapedData = [
            "submission_id" =>$validatedData['submissionid'],
            "vendor_id" =>$vendorId,
            "candidate_id" =>$submission->candidate_id,
            "hiring_manager_id" =>$validatedData['approvingManager'],
            "career_opportunity_id" =>$submission->career_opportunity_id,
            "location_id" =>$validatedData['location'],
            "markup" =>$validatedData['markup'],
            "created_by_id" =>Vendor::getVendorIdByUserId(\Auth::id()),
            "created_by_type" =>3,
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
            'redirect_url' => route('vendor.offer.index') // Redirect back URL for AJAX
        ]);

    }

    // Show a specific career opportunity offer
    public function show($id)
    {
        $workflows = OfferWorkFlow::where('offer_id', $id)->get();
        $offer = CareerOpportunitiesOffer::findOrFail($id);

        return view('vendor.offer.view', compact('offer', 'workflows'));
    }

    // accept career opportunity offer in the database
    public function acceptOffer(Request $request)
    {
        $offer =  CareerOpportunitiesOffer::findOrFail($request->offer_id);
        $submission = $offer->submission;
        // dd($submission);
        if($request->accept_reject == "accept") {
            // if($offer->status != 1 && $offer->status !=13) {
                $offer->offer_accept_date = date("Y-m-d H:i:s");
                $offer->modified_by_id = Vendor::getVendorIdByUserId(\Auth::id());

                $offer->modified_by_type = 3;
                $offer->status = 3;
                $offer->save();

                // submission status update
                $submission->resume_status = 11; //submission status withdrawn
                $submission->save();

                $this->updateOtherRecords($offer);

                if($offer->status == 3){
                    $this->createAutoWorkorder($offer, $offer->careerOpportunity, $submission);
                }
                session()->flash('success', 'Offer accepted successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Offer accepted successfully!',
                    'redirect_url' => route('vendor.offer.index') // Redirect back URL for AJAX
                ]);

            // }
        }
        elseif ($request->accept_reject == "reject") {
            // if($offer->status != 1 && $offer->status !=13) {
            $offer->offer_accept_date = date("Y-m-d H:i:s");

            $offer->modified_by_id = Vendor::getVendorIdByUserId(\Auth::id());
            $offer->modified_by_type = 3;
            $offer->status = 2;
            $offer->save();

            // submission status update
            $submission->resume_status = 6;  ////submission reject
            $submission->save();

//            $this->updateOtherRecords($offer);

            session()->flash('success', 'Offer Rejected successfully!');
            return response()->json([
                'success' => true,
                'message' => 'Offer Rejected successfully!',
                'redirect_url' => route('vendor.offer.index') // Redirect back URL for AJAX
            ]);

            // }
        }

    }

    public function updateOtherRecords($offer)
    {
        // Fetch VendorJobSubmissions with the specified conditions
        $submissionModels = CareerOpportunitySubmission::where('id', '!=', $offer->submission_id)
            ->where('candidate_id', $offer->candidate_id)
            ->whereIn('resume_status', [7, 1, 2, 3, 4, 5, 15])
            ->get();

        // Update each submission
        foreach ($submissionModels as $submission) {
            $submission->update([
                'rejected_type' => 3,
                'rejected_by' => Vendor::getVendorIdByUserId(\Auth::id()),
                'resume_status' => 11,
                'note_for_rejection' => 'Other Offer Accepted',
                'reason_for_rejection' => 66,
                'date_rejected' => now(),
            ]);
        }

        // Fetch offers with the specified conditions
        $offerModels = CareerOpportunitiesOffer::where('id', '!=', $offer->id)
            ->where('candidate_id', $offer->candidate_id)
            ->whereIn('status', [4, 11, 12])
            ->get();

        // Update each offer
        foreach ($offerModels as $offer) {
            $offer->update([
                'status' => 13,
                'withdraw_reason' => 66,
                'notes' => 'Other Offer Accepted',
                'modified_by_id' => Vendor::getVendorIdByUserId(\Auth::id()),
                'date_modified' => now(),
                'offer_rejection_date' => now(),
                'modified_by_type' => 3,
            ]);
        }
    }

    // create auto workorder after offer accept by vendor

    public function createAutoWorkorder($offerModel,$jobModel,$submissionModel){
        // dd($offerModel->start_date);
        $workOrder = new CareerOpportunitiesWorkorder;


        //$total_msp_fee = RatesUtility::returnMspFee($jobData);

        $workOrder->offer_id = $offerModel->id;
        $workOrder->submission_id = $offerModel->submission_id;
        $workOrder->career_opportunity_id = $offerModel->career_opportunity_id;
        $workOrder->created_by_id = $offerModel->created_by_id;
        $workOrder->created_by_type = $offerModel->created_by_type;
        $workOrder->vendor_id = $offerModel->vendor_id;
        $workOrder->candidate_id = $offerModel->candidate_id;
        $workOrder->modified_by_id = $offerModel->modified_by_id;
        $workOrder->modified_by_type = $offerModel->modified_by_type;
        $workOrder->start_date = Carbon::createFromFormat('m/d/Y', $offerModel->start_date)->format('Y-m-d');
        $workOrder->end_date = Carbon::createFromFormat('m/d/Y', $offerModel->end_date)->format('Y-m-d');
        $workOrder->wo_bill_rate = $offerModel->offer_bill_rate;
        $workOrder->wo_pay_rate = $offerModel->offer_pay_rate;
        $workOrder->wo_over_time = $offerModel->over_time;
        $workOrder->wo_double_time = $offerModel->double_time;
        $workOrder->wo_client_over_time = $offerModel->client_overtime;
        $workOrder->wo_client_double_time = $offerModel->client_doubletime;
        $workOrder->vendor_bill_rate = $offerModel->vendor_bill_rate;
        $workOrder->vendor_overtime_rate = $offerModel->vendor_overtime;
        $workOrder->vendor_doubletime_rate = $offerModel->vendor_doubletime;
        $workOrder->approval_manager = $offerModel->hiring_manager_id;
        $workOrder->markup = $offerModel->markup;
        $workOrder->location_id = $offerModel->location_id;
        $workOrder->remote_option = $offerModel->remote_option;
        $workOrder->timesheet_method = 3;
        $workOrder->status = 1;
        $workOrder->accept_date = now();
        $workOrder->wo_release_date = now();
        $workOrder->job_level = $jobModel->job_level;
        $workOrder->job_type =  $jobModel->job_type;
        $workOrder->hiring_manager_id = $jobModel->hiring_manager;
        $workOrder->expenses_allowed = $jobModel->expenses_allowed;
        $workOrder->division_id = $jobModel->division_id;

        if($workOrder->save()){
            Rateshelper::calculateWorkorderEstimates($workOrder,$jobModel);
        }

        $submissionModel->resume_status = 9;
        $submissionModel->save();

      return true;


    }
}
