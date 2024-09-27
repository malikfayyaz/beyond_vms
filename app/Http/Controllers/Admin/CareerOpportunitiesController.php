<?php

namespace App\Http\Controllers\Admin;

use App\Models\GenericData;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\JobTemplates;
use App\Models\CareerOpportunity;
use App\Models\CareerOpportunitiesBu;
use App\Models\JobWorkFlow;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DivisionBranchZoneConfig;
use App\JobWorkflowUpdate;

class CareerOpportunitiesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CareerOpportunity::with('hiringManager', 'workerType')
            ->withCount('submissions');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function ($row) {
                    return $row->hiringManager->full_name ? $row->hiringManager->full_name : 'N/A';
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
                ->rawColumns(['action'])
                ->make(true);
        }

        // Logic to get and display catalog items
        return view('admin.career_opportunities.index'); // Assumes you have a corresponding Blade view
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $careerOpportunity ="";
        $businessUnitsData = "";
        return view('admin.career_opportunities.create', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
        ] );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

                $validatedData = $this->validateJobOpportunity($request);

                $businessUnits = $request->input('businessUnits');
                // dd($businessUnits);

                $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);
                // Handle file upload

                $filename = handleFileUpload($request, 'attachment', 'career_opportunities');
                // Mapping form fields to database column names
                $mappedData = $this->mapJobData($validatedData, $jobTemplate, $request, $filename);
                $job = CareerOpportunity::create( $mappedData );

                $this->syncBusinessUnits($request->input('businessUnits'), $job->id);

                calculateJobEstimates($job);
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
            'category')->findOrFail($id);
        // Optionally, you can dump the data for debugging purposes
        // dd($job); // Uncomment to check the data structure

        // Return the view and pass the job data to it
        return view('admin.career_opportunities.view', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $sessionrole = session('selected_role');
        $careerOpportunity = CareerOpportunity::with('careerOpportunitiesBu')->findOrFail($id);

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
            $newOpportunity->title = $originalOpportunity->title;
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

            $validatedData = $this->validateJobOpportunity($request);

            $job = CareerOpportunity::findOrFail($id);
            $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);
            $filename = handleFileUpload($request, 'attachment', 'career_opportunities', $job->attachment); // Keep existing if no new file
            if($filename == null || $filename == "") {
                $filename = $job->attachment;
            }
            $mappedData = $this->mapJobData($validatedData, $jobTemplate, $request, $filename,$job );
            $job->update($mappedData);

            $this->syncBusinessUnits($request->input('businessUnits'), $job->id);
            calculateJobEstimates($job);
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
        //
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
            'user_subclient_id' => isset($job) ? $job->user_subclient_id  : \Auth::id(),
            'attachment' => $filename,
            'user_id' => isset($job) ? $job->user_id  : \Auth::id(),
            'user_type' => isset($job) ? $job->user_type  : 1,
            'interview_process' => 'Yes',
            'job_type' => 10,
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
                return $row->created_at ? date('Y-m-d H:i:s',strtotime($row->created_at)) : 'N/A';
            })
            ->addColumn('approved_datetime', function($row) {
                return $row->approved_datetime ? $row->approved_datetime : 'N/A';
            })
            ->addColumn('approval_notes', function($row) {
                return $row->approval_notes ? $row->approval_notes : 'N/A';
            })
            ->addColumn('approval_doc', function($row) {
                return $row->approval_doc ? $row->approval_doc : 'N/A';
            })
            ->addColumn('action', function($row){
                 $btn = ' <div x-data="{ open: false }" @keydown.window.escape="open = false">
                        <button @click="open = true" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-red-600">
                            Approve
                        </button>
                         <form @submit.prevent="submitForm" enctype="multipart/form-data" class="space-y-6">
                         ' . csrf_field() . '
                         <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50" style="display: none;">
                            <div class="bg-white rounded-lg shadow-lg max-w-lg mx-4 p-6">
                                <h2 class="text-lg font-bold mb-4">Approve Workflow</h2>
                                <p class="mb-4">
                                <input type="hidden" name="id" value="'.$row->id.'">
                                <input type="hidden" name="action" value="approve">
                                <textarea placeholder="Enter your message" rows="4" class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full"></textarea>
                                <input type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500">

                                </p>
                                <button type="submit"
                                    class="inline-flex justify-center px-4 py-2 bg-blue-500 text-white font-semibold text-sm rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Submit
                                </button>
                                <button @click="open = false" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                    <div x-data="{ open: false }" @keydown.window.escape="open = false">
                        <button @click="open = true" class="inline-flex items-center px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                            Reject
                        </button>

                         <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 z-50" style="display: none;">
                            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4 p-6">
                                <h2 class="text-lg font-bold mb-4">Modal Title</h2>
                                <p class="mb-4">This is a modal description.</p>
                                <button @click="open = false" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>';
                return $btn;
            })
            ->rawColumns(['action'])->make(true);

    }

    public function jobWorkFlowUpdate(Request $request){
        dd($request->all());
    }


}
