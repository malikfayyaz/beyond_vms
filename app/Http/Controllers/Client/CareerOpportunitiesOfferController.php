<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\OfferWorkFlow;
use Illuminate\Support\Facades\Validator;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitySubmission;
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
                    return $row->careerOpportunity->hiringManager ? $row->careerOpportunity->hiringManager->fullname : 'N/A';
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
                    return '<a href="' . route('client.offer.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->make(true);
            }
        return view('client.offer.index');
    }

    // Show the form for creating a new career opportunity offer
    public function create($id)
    {
       $submission =  CareerOpportunitySubmission::findOrFail($id);
       return view('client.offer.create',[
        'submission'=>$submission
         ]);
    }

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
            "created_by_id" =>\Auth::id(),
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
            ? Carbon::createFromFormat('Y/m/d', $validatedData['startDate'])->format('Y-m-d')  : null,
            "end_date" =>!empty($validatedData['endDate'])
            ? Carbon::createFromFormat('Y/m/d', $validatedData['endDate'])->format('Y-m-d')  : null,
         ];
         $offerCreate = CareerOpportunitiesOffer::create( $mapedData );
         calculateVendorRates($offerCreate,$offerCreate->offer_bill_rate,$offerCreate->client_overtime,$offerCreate->client_doubletime);
         calculateOfferEstimates($offerCreate,$jobData);

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
        $workflows = OfferWorkFlow::where('offer_id', $id)->get();
        $offer = CareerOpportunitiesOffer::findOrFail($id);
        return view('client.offer.view', compact('offer','workflows'));
    }
    public function offerworkflowAccept(Request $request)
    {
        $validated = $request->validate([
            'rowId' => 'required|integer',
        ]);
        $workflow = OfferWorkFlow::findOrFail($validated['rowId']);
        $workflow->status = 'Approved'; // Update the status to approved
         $workflow->save();
        $nextWorkflow = OfferWorkFlow::where([
            ['offer_id', '=', $workflow->offer_id],
            ['status', '=', 'Pending'],
            ['email_sent', '=', 0]
        ])
            ->orderBy('id')
            ->first();
        if ($nextWorkflow) {
            $nextWorkflow->email_sent = 1;
            $nextWorkflow->save();
        }
        else{
            $offer = CareerOpportunitiesOffer::findOrFail($workflow->offer_id);
            $offer->status = '4';
            $offer->save();
        }
        $redirectUrl = route('client.offer.show', ['id' => $workflow->offer_id]);
        return response()->json([
            'success' => true,
            'message' => 'Offer Workflow accepted successfully!',
            'redirect_url' => $redirectUrl
        ]);

    }
}
