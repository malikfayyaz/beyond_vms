<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\JobTemplates;
use App\Models\CareerOpportunity;
use App\Models\CareerOpportunitiesBu;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CareerOpportunity::query();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                            $btn = ' <a href="' . route('admin.catalog.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('admin.catalog.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                     $deleteBtn = '<form action="' . route('admin.catalog.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
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
        return view('admin.career_opportunities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $validatedData = $request->validate([
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
            'startDate' => 'required|date_format:Y/m/d',  // Adjusted to match incoming format
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
            'estimatedExpense' => 'nullable|required_if:expensesAllowed,yes',
            'clientName' => 'nullable|required_if:clientBillable,yes',
            'candidateFirstName' => 'nullable|required_if:preIdentifiedCandidate,yes',
            'candidateLastName' => 'nullable|required_if:preIdentifiedCandidate,yes',
            'candidatePhone' => 'nullable|required_if:preIdentifiedCandidate,yes',
            'candidateEmail' => 'nullable|required_if:preIdentifiedCandidate,yes',
            'workerPayRate' => 'nullable|required_if:preIdentifiedCandidate,yes',
            'subLedgerCode' => 'nullable|required_if:subLedgerType,33',

            // nullable fields
            'jobTitleEmailSignature'=>'nullable',
            'candidateMiddleName'=>'nullable',
            // 'job_code' => 'nullable',
        ]);

        $businessUnits = $request->input('businessUnits');
        // dd($businessUnits);

        $jobTemplate = JobTemplates::findOrFail($validatedData['jobTitle']);
        // Handle file upload
       
        $filename = handleFileUpload($request, 'attachment', 'career_opportunities');
         

       
        // Mapping form fields to database column names
        $mappedData = [
            'cat_id' => $validatedData['jobLaborCategory'],
            'template_id' => $validatedData['jobTitle'],
            'title' =>$jobTemplate->job_title,
            'hiring_manager' => $validatedData['hiringManager'],
            'job_level' => $validatedData['jobLevel'],
            'location_id' => $validatedData['workLocation'],
            'currency_id' => $validatedData['currency'],
            'min_bill_rate' => $validatedData['billRate'],
            'user_subclient_id' => \Auth::id(),
            'attachment' => $filename,
            'user_id' => 1,
            'user_type' => '1',
            'interview_process' => 'Yes',
            'jobStatus' => 3,
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
            // 'job_code'=> $validatedData['job_code'],
            'num_openings' => $validatedData['numberOfPositions'],
            'hire_reason_id' => $validatedData['businessReason'],
            // 'terms_accepted' => $validatedData['termsAccepted'],
            'start_date' => Carbon::createFromFormat('Y/m/d', $validatedData['startDate'])->format('Y-m-d'),
            'end_date' =>  Carbon::createFromFormat('Y/m/d', $validatedData['endDate'])->format('Y-m-d'),
           

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

        $job = CareerOpportunity::create( $mappedData );

        $businessUnits = $request->input('businessUnits');

        // Loop through the business units
        foreach ($businessUnits as $unitJson) {
            $unitData = json_decode($unitJson, true);
    
            if (!empty($unitData) && isset($unitData['id'], $unitData['percentage'])) {
                CareerOpportunitiesBu::create([
                    'career_opportunity_id' => $job->id,
                    'bu_unit' => $unitData['id'],
                    'percentage' => $unitData['percentage'],
                ]);
            }
        }

    
        session()->flash('success', 'Job saved successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Job saved successfully!',
            'redirect_url' => route('admin.career-opportunities.index') // Redirect back URL for AJAX
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
