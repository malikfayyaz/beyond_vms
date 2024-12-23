<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesBu;
use App\Models\CareerOpportunity;
use App\Models\Client;
use App\Models\JobTemplates;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Facades\Rateshelper as Rateshelper;
use App\JobWorkflowUpdate;
use App\Models\Setting;
use App\Models\JobWorkFlow;
use App\Models\FormBuilder;


class CareerOpportunitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clientid = Client::getClientIdByUserId(Auth::id());
        $counts = [
            'all_jobs' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->count(),

            'open' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 3)->count(),

            'filled' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 4)->count(),

            'new' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 11)->count(),

            'closed' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 12)->count(),

            'pending' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 1)->count(),

            'sourcing' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 13)->count(),

            'pendingpmo' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 22)->count(),

            'open_pending_release' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->whereIn('jobStatus', [3, 23])->count(),

            'pending_hm' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->whereIn('jobStatus', [1, 23, 24])->count(),

            'quick_create' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->whereIn('jobStatus', [1, 3, 13])->count(),

            'draft' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->where('jobStatus', 2)->count(),

            'active' => CareerOpportunity::whereHas('workFlow', function ($query) use ($clientid) {
                $query->where('client_id', $clientid);
            })->whereIn('jobStatus', [1, 3, 6, 13, 23, 24])->count(),
        ];

        if ($request->ajax()) {
            $clientid = Client::getClientIdByUserId(Auth::id());
            $query = CareerOpportunity::with('hiringManager', 'workerType')
            ->withCount('submissions')
            ->orWhereHas('workFlow', function ($data) use ($clientid) {
            $data->where('client_id', $clientid);
            })
            ->orderby('id', 'desc');
                if ($request->has('type')) {
                    $type = $request->input('type');
                    $status = ''; 
                    switch ($type) {
                        case "All_jobs":
                            $status = ''; // No additional filtering
                            break;
                        case "open":
                            $query->where('jobStatus', 3);
                            break;
                        case "filled":
                            $query->where('jobStatus', 4);
                            break;
                        case "New":
                            $query->where('jobStatus', 11);
                            break;
                        case "closed":
                            $query->where('jobStatus', 12);
                            break;
                        case "Pending":
                            $query->where('jobStatus', 1);
                            break;
                        case "sourcing":
                            $query->where('jobStatus', 13);
                            break;
                        case "pendingpmo":
                            $query->where('jobStatus', 22);
                            break;
                        case "open-pending-release":
                            $query->whereIn('jobStatus', [3, 23]);
                            break;
                        case "pending-hm":
                            $query->whereIn('jobStatus', [1, 23, 24]);
                            break;
                        case "Quickcreate":
                            $query->whereIn('jobStatus', [1, 3, 13]);
                            break;
                        case "draft":
                            $query->where('jobStatus', 2);
                            break;
                        case 'active':
                            $query->whereIn('jobStatus', [1, 3, 6, 13, 23, 24]);
                            break;
                        default:
                            break;
                    }
                }

            return DataTables::of($query)
                ->addColumn('id', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->id . '</span>';
                })
                ->addColumn('title', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->title . '</span>';
                })
                ->addColumn('jobStatus', function ($row) {
                    return (isset($row->jobStatus)) ? $row->getStatus($row->jobStatus) : 'N/A';
                })
                ->addColumn('hiring_manager', function($row) {
                    return (isset($row->hiringManager->full_name)) ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('duration', function($row) {
                    return $row->date_range ? $row->date_range : 'N/A';
                })
                ->addColumn('submissions', function ($row) {
                    return $row->submissions_count;
                })
                ->addColumn('worker_type', function($row) {
                    return $row->workerType ? $row->workerType->title : 'N/A';
                })
                ->addColumn('action', function($row){

                    $btn = ' <a href="' . route('client.career-opportunities.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('client.career-opportunities.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('client.career-opportunities.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                     ' . csrf_field() . method_field('DELETE') . '
                     <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                         <i class="fas fa-trash"></i>
                     </button>
                   </form>';

                    return $btn .$deleteBtn;
                })
                ->rawColumns(['action','id', 'title'])
                ->make(true);
        }

        return view('client.career-opportunities.index', compact('counts'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $formBuilderData = FormBuilder::where('type', 1)
        ->where('status', 'active')
        ->first();

        $careerOpportunity ="";
        $businessUnitsData = "";
        return view('client.career-opportunities.create', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
            'formBuilderData' => $formBuilderData,
            'oldFormData' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $dynamicRules = [];

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^text-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for text inputs
                } elseif (preg_match('/^textarea-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for textarea inputs
                } elseif (preg_match('/^checkbox-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for checkboxes
                } elseif (preg_match('/^radio-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for radio buttons
                } elseif (preg_match('/^select-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for select dropdowns
                } elseif (preg_match('/^file-/', $key)) {
                    $dynamicRules[$key] = 'file'; // Rule for file uploads
                } elseif (preg_match('/^number-/', $key)) {
                    $dynamicRules[$key] = 'numeric'; // Rule for number inputs
                } elseif (preg_match('/^date-/', $key)) {
                    $dynamicRules[$key] = 'date'; // Rule for date inputs
                } elseif (preg_match('/^email-/', $key)) {
                    $dynamicRules[$key] = 'email'; // Rule for email inputs
                }
            }

            $validatednewData = $request->validate($dynamicRules);

            $validatedData = $this->validateJobOpportunity($request);

            $businessUnits = $request->input('businessUnits');
            // dd($businessUnits);

            $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);
            // Handle file upload

            $filename = handleFileUpload($request, 'attachment', 'career_opportunities');
            // Mapping form fields to database column names
            $mappedData = $this->mapJobData($validatedData, $jobTemplate, $request, $filename);
            $job = CareerOpportunity::create( $mappedData );
            $job->job_details = $validatednewData; // Save the validated data as JSON
            $job->save();

            $this->syncBusinessUnits($request->input('businessUnits'), $job->id);

            Rateshelper::calculateJobEstimates($job);
            $jobWorkflow = new JobWorkflowUpdate();
            $jobWorkflow->createJobWorkflow($job);
            session()->flash('success', 'Job saved successfully!');
            return response()->json([
                'success' => true,
                'message' => 'Job saved successfully!',
                'redirect_url' => route('client.career-opportunities.index') // Redirect back URL for AJAX
            ]);
        }catch (ValidationException $e) {
            // Handle the validation error and return a JSON response
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),  // Returns all the validation errors
                'message' => 'Validation failed!',
            ], 422);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = CareerOpportunity::with('hiringManager')->findOrFail($id);
        $jobWorkFlow = JobWorkFlow::where('job_id', $id)->orderby('approval_number', 'ASC')->get();
        $rejectReasons =  Setting::where('category_id', 9)->get();
        $loginClientid = Client::getClientIdByUserId(Auth::id());

        // Optionally, you can dump the data for debugging purposes
        // dd($job); // Uncomment to check the data structure

        // Return the view and pass the job data to it
        return view('client.career-opportunities.view', compact('job', 'jobWorkFlow', 'rejectReasons','loginClientid'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Client::getClientIdByUserId(Auth::id());
        $sessionrole = session('selected_role');
        $careerOpportunity = CareerOpportunity::with('careerOpportunitiesBu')->findOrFail($id);

        $businessUnitsData  = $careerOpportunity->careerOpportunitiesBu->map(function ($item) {
            return [
                'id' => $item->buName->id,
                'unit' => $item->buName->name,
                'percentage' => $item->percentage
            ];
        })->toArray();

        $formBuilderData = FormBuilder::where('type', 1)
        ->where('status', 'active')
        ->first();
        $oldFormData = $careerOpportunity->job_details ? json_decode($careerOpportunity->job_details, true) : [];

        // dd( $businessUnitsData);
        return view('client.career-opportunities.create', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
            'sessionrole' => $sessionrole,
            'formBuilderData' => $formBuilderData,
            'oldFormData' => $oldFormData,
        ] );
        //
    }
    public function copy($id)
    {
        try {
            $originalOpportunity = CareerOpportunity::findOrFail($id);
            $newOpportunity = $originalOpportunity->replicate();
            $newOpportunity->jobstep2_complete = '0';
            $newOpportunity->title = $originalOpportunity->title;
            $newOpportunity->jobStatus = '1';
            $newOpportunity->user_id = Client::getClientIdByUserId(Auth::id());
            $newOpportunity->created_at = Carbon::now();
            $newOpportunity->updated_at = Carbon::now();
            $newOpportunity->save();
            $businessUnitsData = $originalOpportunity->careerOpportunitiesBu->map(function ($item) {
                return [
                    'id' => $item->buName->id,
                    'percentage' => $item->percentage,
                ];
            })->toArray();
            $this->syncBusinessUnits($businessUnitsData, $newOpportunity->id);
            $jobWorkflow = new JobWorkflowUpdate();
            $jobWorkflow->createJobWorkflow($newOpportunity);
            session()->flash('success', 'Career Opportunity Copied successfully!');
            return redirect()->route('client.career-opportunities.edit', $newOpportunity->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error!');
        }
        return redirect()->route('client.career-opportunities.index');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->input('businessUnits'));
        try {
            $dynamicRules = [];

            foreach ($request->all() as $key => $value) {
                if (preg_match('/^text-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for text inputs
                } elseif (preg_match('/^textarea-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for textarea inputs
                } elseif (preg_match('/^checkbox-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for checkboxes
                } elseif (preg_match('/^radio-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for radio buttons
                } elseif (preg_match('/^select-/', $key)) {
                    $dynamicRules[$key] = 'string'; // Rule for select dropdowns
                } elseif (preg_match('/^file-/', $key)) {
                    $dynamicRules[$key] = 'file'; // Rule for file uploads
                } elseif (preg_match('/^number-/', $key)) {
                    $dynamicRules[$key] = 'numeric'; // Rule for number inputs
                } elseif (preg_match('/^date-/', $key)) {
                    $dynamicRules[$key] = 'date'; // Rule for date inputs
                } elseif (preg_match('/^email-/', $key)) {
                    $dynamicRules[$key] = 'email'; // Rule for email inputs
                }
            }

            $validatednewData = $request->validate($dynamicRules);

            $validatedData = $this->validateJobOpportunity($request);

            $job = CareerOpportunity::findOrFail($id);
            $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);
            $filename = handleFileUpload($request, 'attachment', 'career_opportunities', $job->attachment); // Keep existing if no new file
            if($filename == null || $filename == "") {
                $filename = $job->attachment;
            }
            $mappedData = $this->mapJobData($validatedData, $jobTemplate, $request, $filename,$job );
            $job->update($mappedData);
            $job->job_details = $validatednewData; // Save the validated data as JSON
            $job->save();

            $this->syncBusinessUnits($request->input('businessUnits'), $job->id);
            Rateshelper::calculateJobEstimates($job);
            session()->flash('success', 'Job updated successfully!');
            return response()->json([
                'success' => true,
                'message' => 'Job updated successfully!',
                'redirect_url' => route('client.career-opportunities.index')
            ]);
        }catch (ValidationException $e) {
            // Handle the validation error and return a JSON response
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),  // Returns all the validation errors
                'message' => 'Validation failed!',
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    protected function syncBusinessUnits(array $businessUnits, $jobId)
    {
        CareerOpportunitiesBu::where('career_opportunity_id', $jobId)->delete();

        foreach ($businessUnits as $unitData) {
            // If it's already an array, skip json_decode
            if (is_array($unitData) && isset($unitData['id'], $unitData['percentage'])) {
                CareerOpportunitiesBu::create([
                    'career_opportunity_id' => $jobId,
                    'bu_unit' => $unitData['id'],
                    'percentage' => $unitData['percentage'],
                ]);
            } elseif (is_string($unitData)) {
                // If it's a JSON string, decode it
                $decodedData = json_decode($unitData, true);
                if (!empty($decodedData) && isset($decodedData['id'], $decodedData['percentage'])) {
                    CareerOpportunitiesBu::create([
                        'career_opportunity_id' => $jobId,
                        'bu_unit' => $decodedData['id'],
                        'percentage' => $decodedData['percentage'],
                    ]);
                }
            }
        }
    }

    protected function validateJobOpportunity(Request $request)
    {
        return $request->validate([
            'jobLaborCategory' => 'required',
            'jobTitle' => 'required',
            'hiringManager' => 'required',
            'jobLevel' => 'required',
            'workLocation' => 'required',
            'currency' => 'required',
            'billRate' => 'required',
            'maxBillRate' => 'required',
            'preIdentifiedCandidate' => 'required',
            'laborType' => 'required',
            'jobDescriptionEditor' => 'required',
            'qualificationSkillsEditor' => 'required',
            'additionalRequirementEditor' => 'required',
            'division' => 'required',
            'regionZone' => 'required',
            'branch' => 'required',
            'expensesAllowed' => 'required',
            'travelRequired' => 'required',
            'glCode' => 'required',
            'startDate' => 'required|date_format:Y/m/d',
            'endDate' => 'required|date_format:Y/m/d',
            'workerType' => 'required',
            'clientBillable' => 'required',
            'requireOT' => 'required',
            'virtualRemote' => 'required',
            'payment_type' => 'required',
            'timeType' => 'required',
            'estimatedHoursPerDay' => 'required',
            'workDaysPerWeek' => 'required',
            'numberOfPositions' => 'required',
            'businessReason' => 'required',
            'subLedgerType' => 'nullable',
            'attachment' => 'nullable',
            'termsAccepted' => 'accepted',

            // Conditional fields
            'estimatedExpense' => 'nullable|required_if:expensesAllowed,Yes',
            'clientName' => 'nullable|required_if:clientBillable,Yes',
            'candidateFirstName' => 'nullable|required_if:preIdentifiedCandidate,Yes',
            'candidateLastName' => 'nullable|required_if:preIdentifiedCandidate,Yes',
            'candidatePhone' => 'nullable|required_if:preIdentifiedCandidate,Yes',
            'candidateEmail' => 'nullable|required_if:preIdentifiedCandidate,Yes',
            'workerPayRate' => 'nullable|required_if:preIdentifiedCandidate,Yes',
            'subLedgerCode' => 'nullable|required_if:subLedgerType,33',

            // nullable fields
            'jobTitleEmailSignature' => 'nullable',
            'candidateMiddleName' => 'nullable',
            'job_code' => 'nullable',
        ]);
    }
    protected function mapJobData(array $validatedData, $jobTemplate, $request, $filename,$job= null)
    {
        return [
            'cat_id' => $validatedData['jobLaborCategory'],
            'template_id' => $validatedData['jobTitle'],
            'title' => $jobTemplate->job_title,
            'hiring_manager' => $validatedData['hiringManager'],
            'job_level' => $validatedData['jobLevel'],
            'location_id' => $validatedData['workLocation'],
            'currency_id' => $validatedData['currency'],
            'min_bill_rate' => $validatedData['billRate'],
            'user_subclient_id' => isset($job) ? $job->user_subclient_id  : Client::getClientIdByUserId(Auth::id()),
            'attachment' => $filename,
            'user_id' => isset($job) ? $job->user_id  : Client::getClientIdByUserId(Auth::id()),
            'user_type' => isset($job) ? $job->user_type  : 2,
            'interview_process' => 'Yes',
            'job_type' => 10,
            'jobstep2_complete' => 1,
            'jobStatus' => isset($job) ? $job->jobStatus : 1,
            'max_bill_rate' => $validatedData['maxBillRate'],
            'pre_candidate' => $validatedData['preIdentifiedCandidate'],
            'labour_type' => $validatedData['laborType'],
            'description' => $validatedData['jobDescriptionEditor'],
            'skills' => $validatedData['qualificationSkillsEditor'],
            'internal_notes' => $validatedData['additionalRequirementEditor'],
            'division_id' => $validatedData['division'],
            'region_zone_id' => $validatedData['regionZone'],
            'branch_id' => $validatedData['branch'],
            'expenses_allowed' => $validatedData['expensesAllowed'],
            'travel_required' => $validatedData['travelRequired'],
            'gl_code_id' => $validatedData['glCode'],
            'worker_type_id' => $validatedData['workerType'],
            'client_billable' => $validatedData['clientBillable'],
            'background_check_required' => $validatedData['requireOT'],
            'remote_option' => $validatedData['virtualRemote'],
            'payment_type' => $validatedData['payment_type'],
            'type_of_job' => $validatedData['timeType'],
            'hours_per_day' => $validatedData['estimatedHoursPerDay'],
            'day_per_week' => $validatedData['workDaysPerWeek'],
            'job_code' => $validatedData['job_code'],
            'num_openings' => $validatedData['numberOfPositions'],
            'hire_reason_id' => $validatedData['businessReason'],
            'start_date' => Carbon::createFromFormat('Y/m/d', $validatedData['startDate'])->format('Y-m-d'),
            'end_date' => Carbon::createFromFormat('Y/m/d', $validatedData['endDate'])->format('Y-m-d'),
            // Conditional fields
            'expense_cost' => $validatedData['estimatedExpense'] ?? null,
            'client_name' => $validatedData['clientName'] ?? null,
            'pre_name' => $validatedData['candidateFirstName'] ?? null,
            'pre_last_name' => $validatedData['candidateLastName'] ?? null,
            'candidate_phone' => $validatedData['candidatePhone'] ?? null,
            'pre_current_rate' => $validatedData['workerPayRate'] ?? null,
            'candidate_email' => $validatedData['candidateEmail'] ?? null,
            'alternative_job_title' => $validatedData['jobTitleEmailSignature'] ?? null,
            'pre_middle_name' => $validatedData['candidateMiddleName'] ?? null,
            'ledger_type_id' => $validatedData['subLedgerType'] ?? null,
            'ledger_code' => $validatedData['subLedgerCode'] ?? null,
        ];


        return true;
    }

    public function jobWorkFlowApprove(Request $request){
        $jobWorkflow = new JobWorkflowUpdate();
        $jobWorkflow->approveJobWorkFlow($request);
        $successMessage = 'Workflow Accepted successfully';
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  url("client/career-opportunities", $request->job_id) 
        ]);

    }

    public function jobWorkFlowReject(Request $request){
        $jobWorkflow = new JobWorkflowUpdate();
        $jobWorkflow->rejectJobWorkFlow($request);
        $successMessage = 'Workflow Rejected successfully';
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  url("client/career-opportunities", $request->job_id) 
        ]);
    }


}
