<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Activity;
use App\Models\GenericData;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\JobTemplates;
use App\Models\CareerOpportunity;
use App\Models\CareerOpportunityNote;
use App\Models\CareerOpportunitiesBu;
use App\Models\JobWorkFlow;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DivisionBranchZoneConfig;
use App\Models\Setting;
use App\Models\Vendor;
use App\JobWorkflowUpdate;
use App\Models\VendorJobRelease;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesInterview;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\User;
use App\Models\AdminTeamJob;
use App\Models\JobTeamMember;
use App\Models\Client;
use App\Models\FormBuilder;
use App\Facades\Rateshelper as Rateshelper;

class CareerOpportunitiesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $counts = [
            'all_jobs' => CareerOpportunity::count(),
            'open' => CareerOpportunity::where('jobStatus', 3)->count(),
            'filled' => CareerOpportunity::where('jobStatus', 4)->count(),
            'new' => CareerOpportunity::where('jobStatus', 11)->count(),
            'closed' => CareerOpportunity::where('jobStatus', 12)->count(),
            'pending' => CareerOpportunity::where('jobStatus', 1)->count(),
            'sourcing' => CareerOpportunity::where('jobStatus', 13)->count(),
            'pending_pmo' => CareerOpportunity::where('jobStatus', 22)->count(),
            'open_pending_release' => CareerOpportunity::whereIn('jobStatus', [3, 23])->count(),
            'pending_hm' => CareerOpportunity::whereIn('jobStatus', [1, 23, 24])->count(),
            'quick_create' => CareerOpportunity::whereIn('jobStatus', [1, 3, 13])->count(),
            'draft' => CareerOpportunity::where('jobStatus', 2)->count(),
            'active' => CareerOpportunity::whereIn('jobStatus', [1, 3, 6, 13, 23, 24])->count(),
        ];
        if ($request->ajax()) {
        //    dd($request->input('type'));
            $adminId = Admin::getAdminIdByUserId(Auth::id());
            $query = CareerOpportunity::with('hiringManager', 'workerType')
            ->withCount('submissions')
            ->orderby('id', 'desc');

                // Get the action type from the request
                if ($request->has('type')) {
                    $type = $request->input('type');
                    $status = ''; // Initialize status variable

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
                            // No filtering for unknown actions
                            break;
                    }
                }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function ($row) {
                    return (isset($row->hiringManager->full_name)) ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('jobStatus', function ($row) {
                    return (isset($row->jobStatus)) ? $row->getStatus($row->jobStatus) : 'N/A';
                })
                ->addColumn('id', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->id . '</span>';
                })
                ->addColumn('title', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->title . '</span>';
                })
                ->addColumn('duration', function ($row) {
                    return $row->date_range ? $row->date_range : 'N/A';
                })
                ->addColumn('submissions', function ($row) {
                    return $row->submissions_count;
                })
                ->addColumn('worker_type', function ($row) {
                    return $row->workerType ? $row->workerType->title : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('admin.career-opportunities.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('admin.career-opportunities.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('admin.career-opportunities.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                     ' . csrf_field() . method_field('DELETE') . '
                     <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                         <i class="fas fa-trash"></i>
                     </button>
                   </form>';

                    return $btn . $deleteBtn;
                })
                ->rawColumns(['action','id', 'title'])
                ->make(true);
        }
        $logs = Activity::where('log_name', 'career_opportunity')->get();

        // Logic to get and display catalog items
        return view('admin.career_opportunities.index', compact('counts'));
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
        return view('admin.career_opportunities.create', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
            'formBuilderData' => $formBuilderData,
            'oldFormData' => [],
        ] );
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
                        $dynamicRules[$key] = 'file'; // Rule for file uploads
                    } elseif (preg_match('/^number-/', $key)) {
                        $dynamicRules[$key] = 'nullable|numeric'; // Rule for number inputs
                    } elseif (preg_match('/^date-/', $key)) {
                        $dynamicRules[$key] = 'nullable|date_format:m/d/Y';
                    } elseif (preg_match('/^email-/', $key)) {
                        $dynamicRules[$key] = 'nullable|email'; // Rule for email inputs
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
               // dd($mappedData);
                $job = CareerOpportunity::create( $mappedData );
                $job->job_details = $validatednewData; // Save the validated data as JSON
                $job->save();

                $this->syncBusinessUnits($request->input('businessUnits'), $job->id);

                Rateshelper::calculateJobEstimates($job);
                $jobWorkflow = new JobWorkflowUpdate();
                $jobWorkflow->createJobWorkflow($job);
                // get hiring manager id
                // get all the workflow from workflows table
                // update in new table with the job id
                // add sort order in new table
                //
                //dd('workflow should be trigger here');
                session()->flash('success', 'Job saved successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Job saved successfully!',
                    'redirect_url' => route('admin.career-opportunities.index') // Redirect back URL for AJAX
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
        $job = CareerOpportunity::with('hiringManager',
            'division',
            'regionZone',
            'branch',
            'jobType',
            'businessReason',
            'workerType',
            'paymentType',
            'currency',
            'currency.symbol',
            'createdBy',
            'glCode',
            'activities.createdBy',
            'category')->findOrFail($id);
        $jobWorkFlow = JobWorkFlow::where('job_id', $id)->orderby('approval_number', 'ASC')->get();
        $rejectReasons =  Setting::where('category_id', 9)->get();
        $vendors = Vendor::all();
        $vendorRelease = VendorJobRelease::with('vendorName')
            ->where('job_id', $id)
            ->get()
            ->map(function ($release) {
                $release->formatted_job_released_time = formatDateTime($release->job_released_time);
                return $release;
            });
        $admins = Admin::all();
        $clients = Client::all();
        $activityLogs = $job->activities()->with('createdBy')->get();
        return view('admin.career_opportunities.view', compact('job','jobWorkFlow','rejectReasons','vendors','vendorRelease','admins','clients', 'activityLogs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Admin::getAdminIdByUserId(Auth::id());
        $sessionrole = session('selected_role');
        $careerOpportunity = CareerOpportunity::with('careerOpportunitiesBu')->findOrFail($id);

        $formBuilderData = FormBuilder::where('type', 1)
        ->where('status', 'active')
        ->first();
        $oldFormData = $careerOpportunity->job_details ? json_decode($careerOpportunity->job_details, true) : [];

        $businessUnitsData  = $careerOpportunity->careerOpportunitiesBu->map(function ($item) {
            return [
                'id' => $item->buName->id,
                'unit' => $item->buName->name,
                'percentage' => $item->percentage
            ];
        })->toArray();
        return view('admin.career_opportunities.create', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
            'sessionrole' => $sessionrole,
            'formBuilderData' => $formBuilderData,
            'oldFormData' => $oldFormData,
        ] );
    }

    /**
     * Copy the specified resource.
     */
    public function copy($id)
    {
        try {
            $originalOpportunity = CareerOpportunity::with('careerOpportunitiesBu')->findOrFail($id);
            $newOpportunity = $originalOpportunity->replicate();
            $newOpportunity->jobstep2_complete = '0';
            $newOpportunity->jobStatus = '1';
            $newOpportunity->title = $originalOpportunity->title;
            $newOpportunity->user_id = Admin::getAdminIdByUserId(Auth::id());
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
            return redirect()->route('admin.career-opportunities.edit', $newOpportunity->id);

        } catch (\Exception $e) {
            session()->flash('error', 'Error!');
        }

        return redirect()->route('admin.career-opportunities.index');
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
                    $dynamicRules[$key] = 'nullable|string'; // Rule for text inputs
                } elseif (preg_match('/^textarea-/', $key)) {
                    $dynamicRules[$key] = 'nullable|string'; // Rule for textarea inputs
                } elseif (preg_match('/^checkbox-/', $key)) {
                    $dynamicRules[$key] = 'nullable|array'; // Validate as an array
                    $dynamicRules["{$key}.*"] = 'in:true,false';
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

            $validatedData = $this->validateJobOpportunity($request);

            $job = CareerOpportunity::findOrFail($id);
            $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);
            $filename = handleFileUpload($request, 'attachment', 'career_opportunities', $job->attachment);
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
                'redirect_url' => route('admin.career-opportunities.index')
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
        $job = CareerOpportunity::findOrFail($id);
        $job->delete();
        return redirect()->route('admin.career-opportunities.index')
            ->with('success', 'Career opportunity deleted successfully!');
    }

    /**
     * create or update businessunits.
     */
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
            'startDate' => 'required|date_format:m/d/Y',
            'endDate' => 'required|date_format:m/d/Y',
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
            'user_subclient_id' => isset($job) ? $job->user_subclient_id  // If $job exists, use this
                : Admin::getAdminIdByUserId(Auth::id()),
            'attachment' => $filename,
            'user_id' => isset($job) ? $job->user_id  : Admin::getAdminIdByUserId(Auth::id()),
            'user_type' => isset($job) ? $job->user_type  : 1,
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
            'start_date' => Carbon::createFromFormat('m/d/Y', $validatedData['startDate'])->format('Y-m-d'),
            'end_date' => Carbon::createFromFormat('m/d/Y', $validatedData['endDate'])->format('Y-m-d'),
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

    public function jobWorkFlowData(Request $request){
        $data = JobWorkFlow::where('job_id', $request->id)->orderby('approval_number', 'ASC');
        return DataTables::of($data)
             ->addColumn('counter', function($row) {
                static $counter = 0;
                return ++$counter;
            })
            ->addColumn('hiring_manager', function($row) {
                return $row->hiringManager->full_name ? $row->hiringManager->full_name : 'N/A';
            })
            ->addColumn('approval_role_id', function($row) {
                return $row->approval_role_id ? userRoles()[$row->approval_role_id] : 'N/A';
            })
            ->addColumn('approval_required', function($row) {
                return $row->approval_required ? $row->approval_required : 'N/A';
            })
            ->addColumn('approve_reject_by', function($row) {
                return $row->approve_reject_by ? $row->approve_reject_by : 'N/A';
            })
            ->addColumn('created_at', function($row) {
                return $row->created_at ? formatDateTime($row->created_at) : 'N/A';
            })
            ->addColumn('approved_datetime', function($row) {
                return $row->approved_datetime ? formatDateTime($row->approved_datetime) : 'N/A';
            })
            ->addColumn('approval_notes', function($row) {
                return $row->approval_notes ? $row->approval_notes : 'N/A';
            })
            ->addColumn('approval_doc', function($row) {
                return $row->approval_doc ? $row->approval_doc : 'N/A';
            })
            ->addColumn('action', function($row){
                 $btn = ' <div x-data="{ open: false }" @keydown.window.escape="open = false">
                        <button @click="openModal = true; currentRowId = 1" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Accept
                        </button>
                        </div>

                        <div x-data="{ open: false }" @keydown.window.escape="open = false">
                            <button @click="open = true" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                            Reject
                        </button>
                    </div>';
                return $btn;
            })
            ->rawColumns(['action'])->make(true);

    }

    public function jobWorkFlowApprove(Request $request){
        $jobWorkflow = new JobWorkflowUpdate();
        $jobWorkflow->approveJobWorkFlow($request);
        $successMessage = 'Workflow Accepted successfully';
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  url("admin/career-opportunities", $request->job_id)
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
            'redirect_url' =>  url("admin/career-opportunities", $request->job_id)
        ]);

    }

    public function jobApprove(String $id){

        $job = CareerOpportunity::find($id);
        $job->jobstatus = 3;
        $job->save();
        $successMessage = 'Job Approved successfully';
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  url("admin/career-opportunities", $id)
        ]);

    }
    public function jobReject(String $id){
        $job = CareerOpportunity::find($id);
        $job->jobstatus = 2;
        $job->save();
        $successMessage = 'Job Rejected successfully';
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  url("admin/career-opportunities", $id)
        ]);
    }

    public function rejectAdminJob(Request $request){

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');

        if ($sessionrole == "Admin") {
            $userid = Admin::getAdminIdByUserId($userid);
        } elseif ($sessionrole == "Client") {
            $userid = Client::getClientIdByUserId($userid);
        } elseif ($sessionrole == "Vendor") {
            $userid = Vendor::getVendorIdByUserId($userid);
        } elseif ($sessionrole == "Consultant") {
            $userid = Consultant::getConsultantIdByUserId($userid);
        }
        $portal = 'Portal';


        $job = CareerOpportunity::find($request->job_id);
        $job->jobstatus = 5;
        $job->rejected_by = $userid;
        $job->rejected_type = $sessionrole;
        $job->reason_for_rejection = $request->reason;
        $job->note_for_rejection = $request->note;
        $job->date_rejected = date('Y-m-d h:i:s');
        $job->save();

        $successMessage = 'Job Rejected successfully';
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  url("admin/career-opportunities", $request->job_id)
        ]);

    }


     public function releaseJobVendor(Request $request){

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        if ($sessionrole == "Admin") {
            $userid = Admin::getAdminIdByUserId($userid);
        } elseif ($sessionrole == "Client") {
            $userid = Client::getClientIdByUserId($userid);
        } elseif ($sessionrole == "Vendor") {
            $userid = Vendor::getVendorIdByUserId($userid);
        } elseif ($sessionrole == "Consultant") {
            $userid = Consultant::getConsultantIdByUserId($userid);
        }

        $release =   new VendorJobRelease;
        $release->vendor_id  = $request->vendor_id;
        $release->created_by = $userid;
        $release->created_by_type = $sessionrole;
        $release->job_id  = $request->job_id;
        $release->status = 1;
        $release->job_released_time = date('Y-m-d h:i:s');
        $release->save();

        $job = CareerOpportunity::find($request->job_id);
        $job->jobstatus = 13;
        $job->save();
        $successMessage = 'Job release to vendor successfully';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  url("admin/career-opportunities", $request->job_id)
        ]);
    }

    public function jobSubmission(String $id ){
        $submissions = CareerOpportunitySubmission::with(['consultant','vendor','careerOpportunity.hiringManager','location'])->where('career_opportunity_id', $id);

        return DataTables::of($submissions)
            ->addColumn('status', function ($row) {
                return CareerOpportunitySubmission::getSubmissionStatus($row->resume_status);
            })
            ->addColumn('submissionID', function($row) {
                return '<span class="submission-detail-trigger text-blue-500 cursor-pointer" data-id="'
                    . $row->id . '">' . $row->resume_status . '</span>';
            })
            ->addColumn('candidateName', function($row) {
                return $row->consultant ? $row->consultant->first_name : 'N/A';
            })
            ->addColumn('vendor', function($row) {
                return $row->vendor ? $row->vendor->full_name : 'N/A';
            })
            ->addColumn('startDate', function($row) {
                return $row->estimate_start_date ? formatDateTime($row->estimate_start_date) : 'N/A';
            })
            ->addColumn('flag', function($row) {
                return 'N/A';
            })
            ->addColumn('billRate', function($row) {
                return $row->bill_rate ? $row->bill_rate : 'N/A';
            })

            ->addColumn('uniqueID', function($row) {
                return $row->consultant ? $row->consultant->unique_id : 'N/A';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('admin.submission.show', $row->id) . '"
                            class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-eye"></i>
                        </a>';
            })
            ->rawColumns(['status','submissionID','career_opportunity_title','action'])
            ->make(true);
    }

    public function jobTodayInterview($id){
        $interview = CareerOpportunitiesInterview::with(['consultant', 'careerOpportunity', 'duration', 'timezone', 'interviewtype', 'submission', 'interviewDates','location'])
            ->where('career_opportunity_id', $id)
            ->whereHas('interviewDates', function ($query) {
                $query->whereDate('schedule_date', date('Y-m-d') );
            })
            ->orderBy('id', 'desc');

            return DataTables::of($interview)
            ->addColumn('type', function($row) {
                return $row->interviewtype ? $row->interviewtype->title : 'N/A';
            })
            ->addColumn('type', function($row) {
                return $row->interviewtype ? $row->interviewtype->title : 'N/A';
            })
            ->addColumn('id', function($row) {
                return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->id . '</span>';
            })
            ->addColumn('consultant_name', function($row) {
                return $row->consultant ? $row->consultant->full_name : 'N/A';
            })
            ->addColumn('date', function ($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();
                return $primaryDate ? formatDateTime($primaryDate->formatted_schedule_date) : 'N/A';
            })
            ->addColumn('start_time', function($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();

                return $primaryDate ? $primaryDate->formatted_start_time : 'N/A';
            })
            ->addColumn('end_time', function($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();

                return $primaryDate ? $primaryDate->formatted_end_time : 'N/A';
            })
            ->addColumn('location', function($row) {
                return $row->location ? $row->location->name : 'N/A';
            })
            ->addColumn('vendor_name', function($row) {
                return $row->submission ? $row->submission->vendor->full_name : 'N/A';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('admin.interview.edit', $row->id) . '"
                            class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-edit"></i>
                        </a>
                        <a href="' . route('admin.interview.show', $row->id) . '"
                            class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-eye"></i>
                        </a>';
            })
            ->rawColumns(['career_opportunity','id','action'])
            ->make(true);
    }

    public function jobOtherInterview($id){
        $interview = CareerOpportunitiesInterview::with(['consultant', 'careerOpportunity', 'duration', 'timezone', 'interviewtype', 'submission', 'interviewDates','location'])
            ->where('career_opportunity_id', $id)
            ->whereHas('interviewDates', function ($query) {
                $query->whereDate('schedule_date','!=', date('Y-m-d') );
            })
            ->orderBy('id', 'desc');

            return DataTables::of($interview)
            ->addColumn('type', function($row) {
                return $row->interviewtype ? $row->interviewtype->title : 'N/A';
            })
            ->addColumn('type', function($row) {
                return $row->interviewtype ? $row->interviewtype->title : 'N/A';
            })
            ->addColumn('id', function($row) {
                return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->id . '</span>';
            })
            ->addColumn('consultant_name', function($row) {
                return $row->consultant ? $row->consultant->full_name : 'N/A';
            })
            ->addColumn('date', function ($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();
                return $primaryDate ? formatDateTime($primaryDate->formatted_schedule_date) : 'N/A';
            })
            ->addColumn('start_time', function($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();

                return $primaryDate ? $primaryDate->formatted_start_time : 'N/A';
            })
            ->addColumn('end_time', function($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();

                return $primaryDate ? $primaryDate->formatted_end_time : 'N/A';
            })
            ->addColumn('location', function($row) {
                return $row->location ? $row->location->name : 'N/A';
            })
            ->addColumn('vendor_name', function($row) {
                return $row->submission ? $row->submission->vendor->full_name : 'N/A';
            })
            ->addColumn('action', function($row) {
                return '<a href="' . route('admin.interview.edit', $row->id) . '"
                            class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-edit"></i>
                        </a>
                        <a href="' . route('admin.interview.show', $row->id) . '"
                            class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-eye"></i>
                        </a>';
            })
            ->rawColumns(['career_opportunity','id','action'])
            ->make(true);
    }

     public function jobOffer($id){
         $offers = CareerOpportunitiesOffer::with(['consultant','careerOpportunity','hiringManager','vendor'])->where('career_opportunity_id',$id)->orderBy('id', 'desc');
            return DataTables::of($offers)
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('status', function($row) {
                    return CareerOpportunitiesOffer::getOfferStatus($row->status);
                })
                ->addColumn('offer_id', function($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->id . '</span>';
                })
                ->addColumn('bill_rate', function($row) {
                    return $row->offer_bill_rate ? $row->offer_bill_rate : 'N/A';
                })
                ->addColumn('offer_date', function($row) {
                    return $row->offer_accept_date ? formatDateTime($row->offer_accept_date) : 'N/A';
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('admin.offer.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->rawColumns(['career_opportunity','offer_id','action'])
                ->make(true);
    }

    public function jobWorkorder($id){
        $data = CareerOpportunitiesWorkorder::with('hiringManager','vendor','careerOpportunity','location')->where('career_opportunity_id', $id)->orderBy('id', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function($row) {
                return CareerOpportunitiesWorkorder::getWorkorderStatus($row->status);
            })
            ->addColumn('id', function ($row) {
                return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->id . '">' . $row->id . '</span>';
            })
            ->addColumn('consultant_name', function($row) {
                return $row->consultant ? $row->consultant->full_name : 'N/A';
            })
            ->addColumn('start_date', function ($row) {
                return $row->start_date ? formatDateTime($row->start_date) : 'N/A';
            })
            ->addColumn('bill_rate', function ($row) {
                return $row->wo_bill_rate ? $row->wo_bill_rate : 'N/A';
            })
            ->addColumn('location', function($row) {
                return $row->location ? $row->location->name : 'N/A';
            })
            ->addColumn('action', function ($row) {
                $btn = ' <a href="' . route('admin.workorder.show', $row->id) . '"
                   class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                 >
                   <i class="fas fa-eye"></i>
                 </a>';

                return $btn;
            })
            ->rawColumns(['career_opportunity','id','action'])
            ->make(true);
    }
    public function saveNotes(Request $request) //SAVENOTES
    {
        $request->validate([
            'note' => 'required|string',
            'job_id' => 'required|integer'
        ]);
        $note = new CareerOpportunityNote();
        $note->career_opportunity_id = $request->job_id;
        $note->user_id = Auth::id();
        $note->notes = $request->note;
        $note->posted_by_type = Auth::user()->role == 'Client' ? 'Client' : 'Admin';
        $note->save();
        session()->flash('success', 'Notes Added Successfully');
        return response()->json([
            'success' => true,
            'message' => 'Notes Added Successfully',
            'posted_by' => Auth::user()->name,
            'created_at' => $note->created_at->format('m/d/Y H:i A'),
            'redirect_url' => route('admin.career-opportunities.show', $note->career_opportunity_id) // Redirect back URL for AJAX
        ]);
    }

    public function jobRanking($id){
        $query = CareerOpportunitySubmission::where('career_opportunity_id', $id)
            ->whereNotIn('resume_status', [1, 2, 6, 11, 15])
            ->orderByRaw('CASE WHEN bill_rate = 0 THEN 1 ELSE -1 END DESC')
            ->orderByDesc('bill_rate')
            ->orderByDesc('vendor_bill_rate')
            ->get();
        $jobmodel = CareerOpportunity::find($id);
        $workingDays = Rateshelper::number_of_working_days($jobmodel->job_po_duration,$jobmodel->job_po_duration_endDate);
        $hoursPerDay = $jobmodel->hours_per_day;
        $hours = $workingDays * $hoursPerDay;


        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('candidateName', function($row) {
                return $row->consultant ? $row->consultant->full_name : 'N/A';
            })
            ->addColumn('startDate', function($row) {
                return $row->estimate_start_date ? $row->estimate_start_date  : 'N/A';
            })
            ->addColumn('billRate', function ($row) {
                return $row->bill_rate ? $row->bill_rate : 'N/A';
            })
            ->addColumn('submissionCost', function ($row) {
                $jobmodel = CareerOpportunity::find($row->career_opportunity_id);
                $workingDays = Rateshelper::number_of_working_days($jobmodel->job_po_duration,$jobmodel->job_po_duration_endDate);
                $hoursPerDay = $jobmodel->hours_per_day;
                $hours = $workingDays * $hoursPerDay;

                return number_format($row->bill_rate * $hours,2);
            })

            ->addColumn('action', function ($row) {
                $btn = ' <a href="' . route('admin.workorder.show', $row->id) . '"
                   class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                 >
                   <i class="fas fa-eye"></i>
                 </a>';

                return $btn;
            })
            ->rawColumns(['career_opportunity','id','action'])
            ->make(true);
    }

    public function pmoteammember(Request $request, $id)
    {
        if (isset($request->user_id)) {
            $existingMember = AdminTeamJob::where('career_opportunity_id', $id)
                ->where('user_id', $request->user_id)
                ->first();
            if ($existingMember) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already added as a PMO team member!',
                ], 409);
            }
            $data = new AdminTeamJob;
            $data->career_opportunity_id = $id;
            $data->user_id = $request->user_id;
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'PMO Team member Added successfully!',
            ], status: 201);
        }

        // For DataTables AJAX request
        if ($request->ajax()) {
            $query = AdminTeamJob::where('career_opportunity_id', $id)->with('user')->get();
            return DataTables::of($query)
                ->addColumn('name', function ($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->addColumn('email', function ($row) {
                    return $row->user ? $row->user->email : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = "<div x-data=\"{
                    deleteRecord(id) {
                        if (confirm('Are you sure?')) {
                            fetch('" . route('admin.pmoteammemberDelete', $row->id) . "', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '" . csrf_token() . "',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                $('#dataTable').DataTable().ajax.reload();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                        }
                    }
                }\">
                    <button @click=\"deleteRecord(" . $row->id . ")\" class=\"text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent\">
                        <i class=\"fas fa-trash\"></i>
                    </button>
                </div>";
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function jobteammember(Request $request, $id)
    {
        // Handle saving new team member
        if (isset($request->user_id)) {
            // Check if the user is already added as a team member for this job
            $existingTeamMember = JobTeamMember::where('career_opportunity_id', $id)
                ->where('user_id', $request->user_id)
                ->first();

            if ($existingTeamMember) {
                return response()->json([
                    'success' => false,
                    'message' => 'User already added as a team member!',
                ], 409);
            }

            // Add new team member if not already added
            $data = new JobTeamMember;
            $data->career_opportunity_id = $id;
            $data->user_id = $request->user_id;
            $data->status = 0;
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Team member Added successfully!',
            ], status: 201);
        }

        // If the request is an AJAX call, return the DataTable response
        if ($request->ajax()) {
            $query = JobTeamMember::where('career_opportunity_id', $id)
                ->where('status', '!=', 2)
                ->get();

            return DataTables::of($query)
                ->addColumn('name', function($row) {
                    return $row->user ? $row->user->name : 'N/A';
                })
                ->addColumn('email', function($row) {
                    return $row->user ? $row->user->email : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = "<div x-data=\"{
                    deleteRecord(id) {
                        if (confirm('Are you sure?')) {
                            fetch('" . route('admin.jobteammemberDelete', $row->id) . "', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '" . csrf_token() . "',
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                $('#dataTable1').DataTable().ajax.reload();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                        }
                    }
                }\">
                    <button @click=\"deleteRecord(" . $row->id . ")\" class=\"text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent\">
                        <i class=\"fas fa-trash\"></i>
                    </button>
                </div>";

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }


    public function jobteammemberDelete($id){
        $data =  JobTeamMember::find($id);
        $data->status = 2;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Data saved successfully!',
        ]);
    }

    public function pmoteammemberDelete($id){
        $data =  AdminTeamJob::find($id);
        $data->status = 2;
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Data saved successfully!',
        ]);
    }

    public function quickcreate(){

        $careerOpportunity ="";
        $businessUnitsData = "";

        return view('admin.career_opportunities.quickcreate', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
        ]);
    }

    public function quickjobStore(Request $request){
        $validatedData = $request->validate([
            'jobLaborCategory' => 'required',
            'jobTitle' => 'required',
            'hiringManager' => 'required',
            'jobLevel' => 'required',
            'workLocation' => 'required',
            'virtualRemote' => 'required',
            'businessUnit' => 'required',
            'division' => 'required',
            'regionZone' => 'required',
            'branch' => 'required',
            'workerType' => 'required',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'additionalRequirementEditor' => 'required',
            'buJustification' => 'required',
            'corporate_legal' => 'required',
            'expectedCost' => 'required_if:corporate_legal,Yes|nullable|numeric',
            'acknowledgement' => 'required|in:true,false',
            'estimatedHoursPerDay' => 'required|numeric',
            'workDaysPerWeek' => 'required|numeric',
            'numberOfPositions' => 'required|numeric',
            'preIdentifiedCandidate' => 'required',
            'billRate' => 'required',
            'maxBillRate' => 'required',
            'candidateFirstName' => 'required_if:preIdentifiedCandidate,Yes|nullable|string',
            'candidateLastName' => 'required_if:preIdentifiedCandidate,Yes|nullable|string',
            'candidatePhone' => 'required_if:preIdentifiedCandidate,Yes|nullable|string',
            'candidateEmail' => 'required_if:preIdentifiedCandidate,Yes|nullable|email',
            'payment_type' => 'required',
            'jobTitleEmailSignature' => 'nullable',
            'candidateMiddleName' => 'nullable',
            'job_code' => 'nullable|numeric',
        ]);

        $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);

        $careerOpportunity = new CareerOpportunity();
        $careerOpportunity->cat_id = $validatedData['jobLaborCategory'];
        $careerOpportunity->template_id = $validatedData['jobTitle'];
        $careerOpportunity->hiring_manager = $validatedData['hiringManager'];
        $careerOpportunity->job_level = $validatedData['jobLevel'];
        $careerOpportunity->job_code = $validatedData['job_code'];
        $careerOpportunity->location_id = $validatedData['workLocation'];
        $careerOpportunity->remote_option = $validatedData['virtualRemote'];
        $careerOpportunity->division_id = $validatedData['division'];
        $careerOpportunity->region_zone_id = $validatedData['regionZone'];
        $careerOpportunity->branch_id = $validatedData['branch'];
        $careerOpportunity->worker_type_id = $validatedData['workerType'];
        $careerOpportunity->start_date = $validatedData['startDate'];
        $careerOpportunity->end_date = $validatedData['endDate'];
        $careerOpportunity->internal_notes = $validatedData['additionalRequirementEditor'];
        // $careerOpportunity->business_justification = $validatedData['buJustification'];
        // $careerOpportunity->corporate_legal = $validatedData['corporate_legal'];
        // $careerOpportunity->expected_cost = $validatedData['expectedCost'];
        // $careerOpportunity->acknowledgement = $validatedData['acknowledgement'];
        $careerOpportunity->hours_per_day = $validatedData['estimatedHoursPerDay'];
        $careerOpportunity->day_per_week = $validatedData['workDaysPerWeek'];
        $careerOpportunity->num_openings = $validatedData['numberOfPositions'];
        $careerOpportunity->pre_candidate = $validatedData['preIdentifiedCandidate'];
        $careerOpportunity->pre_name = $validatedData['candidateFirstName'];
        $careerOpportunity->pre_middle_name = $validatedData['candidateMiddleName'];
        $careerOpportunity->pre_last_name = $validatedData['candidateLastName'];
        $careerOpportunity->candidate_phone = $validatedData['candidatePhone'];
        $careerOpportunity->candidate_email = $validatedData['candidateEmail'];
        $careerOpportunity->alternative_job_title = $validatedData['jobTitleEmailSignature'];
        $careerOpportunity->title = $jobTemplate->job_title;
        $careerOpportunity->user_id = isset($job) ? $job->user_id  : Admin::getAdminIdByUserId(Auth::id());
        $careerOpportunity->user_type = isset($job) ? $job->user_type  : 1;
        $careerOpportunity->user_subclient_id = isset($job) ? $job->user_subclient_id  // If $job exists, use this
                : Admin::getAdminIdByUserId(Auth::id());
        $careerOpportunity->currency_id = 2;
        $careerOpportunity->gl_code_id = 15;
        $careerOpportunity->hire_reason_id = 37;
        $careerOpportunity->jobStatus = 1;
        $careerOpportunity->payment_type = $validatedData['payment_type'];
        $careerOpportunity->job_type = 10;
        $careerOpportunity->labour_type = 30;
        $careerOpportunity->min_bill_rate = $validatedData['billRate'];
        $careerOpportunity->max_bill_rate = $validatedData['maxBillRate'];
       
        $careerOpportunity->save();

        $businessUnits = [
            [
                'id' => $validatedData['businessUnit'],  
                'percentage' => 100,
            ]
        ];

        $this->syncBusinessUnits($businessUnits, $careerOpportunity->id);
        

        Rateshelper::calculateJobEstimates($careerOpportunity);
        $jobWorkflow = new JobWorkflowUpdate();
        $jobWorkflow->createJobWorkflow($careerOpportunity);

        session()->flash('success', 'Job saved successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Job saved successfully!',
            'redirect_url' => route('admin.career-opportunities.index') // Redirect back URL for AJAX
        ]);
    }

}
