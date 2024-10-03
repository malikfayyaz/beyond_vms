<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesWorkorder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesWorkOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CareerOpportunitiesWorkorder::with('hiringManager','vendor','careerOpportunity');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function ($row) {
                    return $row->hiringManager->full_name ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function($row) {
                    return $row->careerOpportunity ? $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' : 'N/A';
                })
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
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
                    $btn = ' <a href="' . route('vendor.workorder.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Logic to get and display catalog items
        return view('vendor.workorder.index'); // Assumes you have a corresponding Blade view
    }

    public function store(Request $request)
    {
       
        // Define your validation rules
        $rules = [
            'codeOfConduct' => 'required|boolean',
            'dataPrivacy' => 'required|boolean',
            'nonDisclosure' => 'required|boolean',
            'criminalBackground' => 'required|boolean',
            'accountManager' => 'required|integer',
            'recruitmentManager' => 'required|integer',
            'workorder_id'=> 'required|integer',
        ];
        
        $messages = [
            'codeOfConduct.required' => 'You must agree to the Code of Conduct.',
            'dataPrivacy.required' => 'You must agree to the Data Privacy Policy.',
            'nonDisclosure.required' => 'You must agree to the Non-Disclosure Agreement.',
            'criminalBackground.required' => 'You must agree to the Criminal Background Check.',
            'accountManager.required' => 'An Account Manager is required.',
            'recruitmentManager.required' => 'A Recruitment Manager is required.',
            // Add more custom messages as needed
        ];


        // dd($validator );
        // If validation fails, return JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity status
        }
        $workorder = CareerOpportunitiesWorkorder::findOrFail($request->workorder_id);
        $submission = $workorder->submission;
        if(isset($submission)) {
            $submission->emp_msp_account_mngr = $request->recruitmentManager;
			$submission->save();
        }
        $workorder->location_tax = removeComma($request->lacationTax);
        dd($request);
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
        $mapedData = [
            "submission_id" =>$validatedData['submissionid'],
            "vendor_id" =>$submission->vendor_id,
            "candidate_id" =>$submission->candidate_id,
            "hiring_manager_id" =>$validatedData['approvingManager'],
            "career_opportunity_id" =>$submission->career_opportunity_id,
            "location_id" =>$validatedData['location'],
            "markup" =>$validatedData['markup'],
            "created_by_id" =>\Auth::id(),
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
        calculateVendorRates($offerCreate,$offerCreate->offer_bill_rate,$offerCreate->client_overtime,$offerCreate->client_doubletime);
        calculateOfferEstimates($offerCreate,$jobData);
        offerHelper::createOfferWorkflow($offerCreate);
        session()->flash('success', 'Offer saved successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Offer saved successfully!',
            'redirect_url' => route('vendor.offer.index') // Redirect back URL for AJAX
        ]);

    }
    public function show($id)
    {
        $workorder = CareerOpportunitiesWorkorder::findOrFail($id);
        return view('vendor.workorder.view', compact('workorder'));
    }
}
