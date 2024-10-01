<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunity;
use App\Models\Markup;
use App\Models\Country;
use App\Models\Location;
use App\Models\Vendor;
use App\Models\User;
use App\Models\CareerOpportunitySubmission;
use App\Models\Consultant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $submissions = CareerOpportunitySubmission::with(['consultant','vendor','careerOpportunity.hiringManager','location'])->get();

            return DataTables::of($submissions)
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('unique_id', function($row) {
                    return $row->consultant ? $row->consultant->unique_id : 'N/A';
                })
                ->addColumn('hiring_manager_name', function($row) {
                    // Access the hiring manager through the careerOpportunity relationship
                    return $row->careerOpportunity && $row->careerOpportunity->hiringManager 
                        ? $row->careerOpportunity->hiringManager->full_name 
                        : 'N/A';
                })
                ->addColumn('location_name', function($row) {
                    return $row->location->name; // Access the attribute
                })
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('career_opportunity_title', function($row) {
                    return $row->careerOpportunity ? $row->careerOpportunity->title : 'N/A';
                })
                ->addColumn('worker_type', function ($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('vendor.submission.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->make(true);
        }
        return view('vendor.submission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $career_opportunity = CareerOpportunity::findOrFail($id);
        $markup = Markup::whereIn('category_id', [$career_opportunity->cat_id])
                ->orWhereIn('location_id', [$career_opportunity->location_id])
                ->orWhereIn('vendor_id', [\Auth::id()])
                ->first();
                $markupValue = $markup ? $markup->markup_value : 0;
        $location = Location::byStatus();  
        // dd($countries);     
        $vendor = Vendor::where('user_id', \Auth::id())->first();
        return view('vendor.submission.create',[
            'career_opportunity'=>$career_opportunity,
            'markup'=>$markupValue,
            'location'=> $location,'vendor'=> $vendor ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // Define your validation rules
        $rules = [
            'candidateType' => 'required|integer',
            'candidateSelection' => 'nullable|required_if:candidateType,2',
            'dobDate' => 'required|date_format:m/d/Y',
            'lastFourNationalId' => 'required|digits:4',
            'payRate' => 'required|numeric|min:0',
            'billRate' => 'required|numeric|min:0',
            'exceedBillRate' => 'required|numeric|min:0',
            'preferredName' => 'nullable|string',
            'gender' => 'required|integer',
            'race' => 'required|integer',
            'adjustedMarkup' => 'nullable|numeric',
            'jobId' => 'required|integer',
            'vendorMarkup' => 'nullable|numeric',
            'workLocation' => 'required|integer',
            'preferredLanguage' => 'nullable|string',
            'candidateEmail' => 'required|email',
            'phoneNumber' => 'nullable',
            'supplierAccountManager' => 'required|integer',
            'availableDate' => 'required|date_format:m/d/Y',
            'needSponsorship' => 'required|in:yes,no',
            'workedForGallagher' => 'required|in:yes,no',
            'gallagherCapacity' => 'nullable|required_if:workedForGallagher,Yes',
            'gallagherStartDate' => 'nullable|required_if:workedForGallagher,Yes',
            'gallagherLastDate' => 'nullable|required_if:workedForGallagher,Yes',
            'willingToCommute' => 'required|in:yes,no',
            'virtualRemoteCandidate' => 'required|in:yes,no',
            'rightToRepresent' => 'required|in:yes,no',
            'virtualCity' => 'nullable|required_if:workedForGallagher,Yes',
            
            'availToInterviewNotes' => 'nullable|string',
            'comment' => 'nullable|string',
            'jobId' => 'required|integer',
            'category_id' => 'nullable',
            'over_time'=> 'nullable',
            // 'rateType' => 'required|string',
            'candidateFirstName' => 'required|string',
            'candidateMiddleName' => 'nullable|string',
            'candidateLastName' => 'required|string',
            'candidateHomeCity' => 'nullable|string',
            // File validation rules
            'resumeUpload' => 'required|file|mimes:doc,docx,pdf|max:5120', // Required, PDF, DOC, DOCX, max 5MB
            'additionaldoc' => 'nullable|file|mimes:doc,docx,pdf|max:5120', // Optional, PDF, DOC, DOCX, max 5MB
        ];

    // Custom error messages (optional)
        $messages = [
            'resumeUpload.required' => 'Please upload your resume.',
            'resumeUpload.mimes' => 'The resume must be a file of type: pdf, doc, docx.',
            'resumeUpload.max' => 'The resume may not be greater than 5MB.',
            'additionaldoc.mimes' => 'The additional document must be a file of type: pdf, doc, docx.',
            'additionaldoc.max' => 'The additional document may not be greater than 5MB.',
            // Add more custom messages as needed
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);
            
        
        // dd($validator );
        // If validation fails, return JSON response with errors
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity status
        }
        $consultant = $request->filled('candidateSelection') 
        ?Consultant::where('user_id', $request->candidateSelection)->first()
        : new Consultant();
        // Handle the file upload for resume, use the existing resume if it's not uploaded
        $resume = $request->hasFile('resumeUpload') 
        ? handleFileUpload($request, 'resumeUpload', 'candidate_resume') 
        : ($consultant->resume ?? null);

        // Handle the file upload for additional documents, use the existing document if it's not uploaded
        $additional_doc = $request->hasFile('additionaldoc') 
        ? handleFileUpload($request, 'additionaldoc', 'candidate_additional_doc') 
        : ($consultant->additional_document ?? null);
     
        
        $mappedData = $this->mapConsultantData($validator->validated(), $consultant, $request,$resume, $additional_doc);
        
        // Define an array of fields that need to be processed
            $fieldsToProcess = [
                'over_time',
                'payRate',
                'billRate',
                'over_time_rate',
                'double_time_rate',
                'client_over_time_rate',
                'client_double_time_rate'
            ];

        // Initialize an empty array to store processed values
        $processedValues = [];

        // Iterate over each field, check if it's present in the request, and apply removeComma
        foreach ($fieldsToProcess as $field) {
            if ($request->has($field)) {
                $processedValues[$field] = removeComma($request->input($field));
            }
        }

        // Assign processed values to individual variables, if needed
       

         $submission = new CareerOpportunitySubmission();
    
         $submission_resume =  handleFileUpload($request, 'resumeUpload', 'submission_resume');
 
         // Handle the file upload for additional documents, use the existing document if it's not uploaded
         $submission_additional_doc =  handleFileUpload($request, 'additionaldoc', 'submission_additional_doc');
      
         $mappedSubmissionData = $this->mapSubmissionData($validator->validated(), $submission, $request,$processedValues,$mappedData,$submission_resume, $submission_additional_doc);
         $submissionCreate = CareerOpportunitySubmission::create( $mappedSubmissionData );
         session()->flash('success', 'Submission saved successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Submission saved successfully!',
                    'redirect_url' => route('vendor.career-opportunities.index') // Redirect back URL for AJAX
                ]);
        }   


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $submission = CareerOpportunitySubmission::findOrFail($id);
        
        // Return a view or other response with the submission details
        return view('vendor.submission.view', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    protected function mapConsultantData(array $validatedData, $consultant, $request,$resume, $additional_doc)
    {
        $user = User::where('email', $validatedData['candidateEmail'])->first();
        if (!$user) {
        
            $user = new User;      
            $user->name = $validatedData['candidateFirstName'].' '.$validatedData['candidateMiddleName'].' '.$validatedData['candidateLastName'];
            $user->email = $validatedData['candidateEmail'];
            $user->password = Hash::make('password');
            $user->is_consultant = 1;

            $user->save();
        }
        
        $mappedData = [
            'first_name' => $validatedData['candidateFirstName'],
            'middle_name' => $validatedData['candidateMiddleName'],
            'last_name' => $validatedData['candidateLastName'],
            'dob' =>  Carbon::createFromFormat('d/m/Y', $validatedData['dobDate'])->format('Y-m-d'),
            'national_id' =>$validatedData['lastFourNationalId'],
            // 'email' =>$validatedData['candidateEmail'],
            'vendor_id' =>\Auth::id(),
            'unique_id'=>generateUniqueUserCode(),
            'resume'=>$resume,
            'gender' =>$validatedData['gender'],
            'ethnicity' =>$validatedData['race'],
            'user_id'=>  $user->id,
            'addtional_document' =>$additional_doc,
            'profile_approve' =>'Yes',
            'profile_approved_date'=>date('Y-m-d H:i:s'),
            'is_enable' =>1,
        ];
        // Check if the consultant with the given user_id already exists
            $consultantModel = Consultant::where('user_id', $user->id)->first();

            if ($consultantModel) {
                // Update the existing consultant record
                $consultantModel->update($mappedData);
            } else {
                // Create a new consultant record
                $consultantModel = Consultant::create($mappedData);
            }

        return $consultantModel->id;
    }

    protected function mapSubmissionData(array $validatedData, $submission, $request,$processedValues,$consultantModel,$submission_resume, $submission_additional_doc)
    {
        $over_time= $processedValues['over_time'] ?? null;
        $payrate = $processedValues['payRate'] ?? null;
        $bill_rate = $processedValues['billRate'] ?? null;
        $vendor_bill_rate = $processedValues['billRate'] ?? null;
        // $over_time_rate = $processedValues['over_time_rate'] ?? null;
        $double_time_rate = $processedValues['double_time_rate'] ?? null;
        $client_over_time_rate = $processedValues['client_over_time_rate'] ?? null;
        $client_double_time_rate = $processedValues['client_double_time_rate'] ?? null;
        return [
            'created_by_user' => \Auth::id(),
            'candidate_id' => $consultantModel,
            'career_opportunity_id' => $validatedData['jobId'],
            'location_id' =>  $validatedData['workLocation'],
            'current_location'=>$validatedData['candidateHomeCity'],
            'category_id' =>$validatedData['category_id'],
            'markup' =>$validatedData['vendorMarkup'],
            'vendor_id' =>\Auth::id(),
            'actuall_markup' =>$validatedData['adjustedMarkup'],
            'vendor_bill_rate' =>$vendor_bill_rate,
            'candidate_pay_rate' =>$payrate,
            'bill_rate' =>$bill_rate,
            'client_double_time_rate' =>$client_double_time_rate,
            'over_time_rate' =>$over_time,
            'client_over_time_rate' => $client_over_time_rate,
            'double_time_rate' =>$double_time_rate,
            'require_employment_visa_sponsorship' =>$validatedData['rightToRepresent'],
            'is_legally_authorized' =>$validatedData['needSponsorship'],
            'remote_contractor' =>$validatedData['virtualRemoteCandidate'],
            'retiree' =>$validatedData['workedForGallagher'],
            'capacity' =>$validatedData['gallagherCapacity'],
            'willing_relocate' =>$validatedData['willingToCommute'],
            'emp_msp_account_mngr' =>$validatedData['supplierAccountManager'],
            'start_date' =>!empty($validatedData['gallagherStartDate']) 
            ? Carbon::createFromFormat('d/m/Y', $validatedData['gallagherStartDate'])->format('Y-m-d')
            : null,
            'end_date' => !empty($validatedData['gallagherLastDate']) 
                ? Carbon::createFromFormat('d/m/Y', $validatedData['gallagherLastDate'])->format('Y-m-d') 
                : null,

            'estimate_start_date' => !empty($validatedData['availableDate']) 
                ? Carbon::createFromFormat('d/m/Y', $validatedData['availableDate'])->format('Y-m-d') 
                : null,
            'virtual_city'=>$validatedData['virtualCity'],
            'resume'=>$submission_resume,       
            'optional_document' =>$submission_additional_doc,
            'comment'=>$validatedData['comment'],
            'notes'=>$validatedData['availToInterviewNotes'],
            'shortlisted_date' =>date('Y-m-d H:i:s'),
            'resume_status' =>1,
            'release_to_client' =>1,
        ];


        return true;
    }
}
