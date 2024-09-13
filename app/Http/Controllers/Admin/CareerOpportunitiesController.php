<?php

namespace App\Http\Controllers\Admin;

use App\Models\GenericData;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\JobTemplates;
use App\Models\CareerOpportunity;
use App\Models\CareerOpportunitiesBu;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DivisionBranchZoneConfig;

class CareerOpportunitiesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CareerOpportunity::with('hiringManager','workerType')
            ->select('career_opportunities.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function($row) {
                    return $row->hiringManager ? $row->hiringManager->first_name : 'N/A';
                })
                ->addColumn('worker_type', function($row) {
                    return $row->workerType ? $row->workerType->title : 'N/A';
                })
/*            $data = CareerOpportunity::query();
            return Datatables::of($data)
                    ->addIndexColumn()*/
                    ->addColumn('action', function($row){

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

                            return $btn .$deleteBtn;
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
            'careerBU.buName',
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
        $careerOpportunity = CareerOpportunity::with('careerOpportunitiesBu')->findOrFail($id);

        $businessUnitsData  = $careerOpportunity->careerOpportunitiesBu->map(function ($item) {
            return [
                'id' => $item->id,
                'unit' => $item->buName->name,
                'percentage' => $item->percentage
            ];
        })->toArray();
        // dd( $businessUnitsData);
        return view('admin.career_opportunities.create', [
            'careerOpportunity' => $careerOpportunity,
            'businessUnitsData' => $businessUnitsData,
        ] );
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request);
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
        foreach ($businessUnits as $unitJson) {
            $unitData = json_decode($unitJson, true);
            if (!empty($unitData) && isset($unitData['id'], $unitData['percentage'])) {
                CareerOpportunitiesBu::create([
                    'career_opportunity_id' => $jobId,
                    'bu_unit' => $unitData['id'],
                    'percentage' => $unitData['percentage'],
                ]);
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

    /**
     * Display the specified resource.
     */
}
