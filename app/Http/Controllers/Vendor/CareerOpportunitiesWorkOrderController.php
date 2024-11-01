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
        return view('vendor.workorder.index'); // Assumes you have a corresponding Blade view
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
    {
        $workorder = CareerOpportunitiesWorkorder::findOrFail($id);
        return view('vendor.workorder.view', compact('workorder'));
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
