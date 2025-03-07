<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Facades\Rateshelper as Rateshelper;
use App\Facades\CareerOpportunitiesContract as contractHelper;
use App\Models\Admin;
use App\Models\User;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunity;
use App\Models\ContractNote;
use App\Models\OfferWorkFlow;
use App\Models\ContractBudgetWorkflow;
use App\Models\ContractExtensionWorkflow;
use App\Models\CareerOpportunitiesContract;
use App\Models\contractAdditionalBudget;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\CareerOpportunitySubmission;
use App\Models\ContractRate;
use App\Models\Consultant;
use App\Models\ContractExtensionRequest;
use App\Models\ContractEditHistory;
use App\Models\TimesheetProject;
use App\Models\ContractRateEditRequest;
use App\Models\CareerOpportunitiesBu;
use App\Models\ContractRatesEditWorkflow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CareerOpportunitiesContractController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $counts = [
            'active' => CareerOpportunitiesContract::where('status', 1)->count(),
            'cancelled' => CareerOpportunitiesContract::whereIn('status', [2, 6])->count(),
            'additional_budget' => CareerOpportunitiesContract::whereHas('contractAdditionalBudgetRequest')->count(),
            'ext_req' => CareerOpportunitiesContract::whereHas('extensionRequest')->count(),
            'rate_change' => CareerOpportunitiesContract::whereHas('contractRateEditRequest')->count(),
        ];

        if ($request->ajax()) {

            $adminId = Admin::getAdminIdByUserId(Auth::id());

            $data = CareerOpportunitiesContract::with('hiringManager','careerOpportunity','workOrder.vendor','location');
            
            if ($request->has('type')) {
                $type = $request->input('type');
                switch ($type) {
                    case 'active':
                        $data->where('status', 1);
                        break;
                    case 'cancelled':
                        $data->whereIn('status', [2, 6]);
                        break;
                    case 'additional_budget':
                        $data->has('contractAdditionalBudgetRequest');
                        break;
                    case 'ext_req':
                        $data->has('extensionRequest');
                        break;
                    case 'rate_change':
                        $data->has('contractRateEditRequest');
                        break;
                    // Add additional cases as needed
                    default:
                        break; // Show all submissions if no type is specified
                }
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return (isset($row->status)) ? $row->getContractStatus($row->status) : 'N/A';
                })
                ->addColumn('hiring_manager', function ($row) {
                    return (isset($row->hiringManager->full_name)) ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->careerOpportunity->id . '">' . $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' . '</span>';
                })
                ->addColumn('vendor_name', function ($row) {
                    // Access vendor name via the workOrder relationship
                    return $row->workOrder && $row->workOrder->vendor
                        ? $row->workOrder->vendor->full_name
                        : 'N/A';
                })
                ->addColumn('duration', function ($row) {
                    return $row->date_range ? $row->date_range : 'N/A';
                })
                ->addColumn('worker_type', function($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('location', function($row) {
                    return $row->location ? $row->location->name : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('admin.contracts.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('admin.contracts.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('admin.contracts.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                     ' . csrf_field() . method_field('DELETE') . '
                     <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                         <i class="fas fa-trash"></i>
                     </button>
                   </form>';

                    return $btn . $deleteBtn;
                })
                ->rawColumns(['career_opportunity','action'])
                ->make(true);
        }
        // Logic to get and display catalog items
        return view('admin.contract.index', compact('counts')); // Assumes you have a corresponding Blade view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //    dd($request);
        $rules = [

            'timesheetType' => 'required|integer',
            'candidateSourcing' => 'required|integer',
            'workorder_id' =>'required|integer',
         ];
         $messages = [

            // Add more custom messages as needed
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity status
        }
        $validatedData = $validator->validated();
        $workOrderModel = CareerOpportunitiesWorkorder::findOrFail($request->workorder_id);
        $contractModel = new CareerOpportunitiesContract();
                $contractModel->workorder_id = $workOrderModel->id;
                $contractModel->offer_id = $workOrderModel->offer_id;
                $contractModel->created_by = Admin::getAdminIdByUserId(Auth::id());
                $contractModel->created_by_type = 1;
                $contractModel->submission_id = $workOrderModel->submission_id;
                $contractModel->career_opportunity_id = $workOrderModel->career_opportunity_id;
                $contractModel->hiring_manager_id = $workOrderModel->hiring_manager_id;
                $contractModel->vendor_id = $workOrderModel->vendor_id;
                $contractModel->candidate_id = $workOrderModel->candidate_id;
                $contractModel->status = 1;
                $contractModel->start_date = Carbon::createFromFormat('m/d/Y', $request->startDate)->format('Y-m-d');
                $contractModel->end_date = Carbon::createFromFormat('m/d/Y', $request->endDate)->format('Y-m-d');
                $contractModel->type_of_timesheet = $request->timesheetType;
                $contractModel->location_id = $request->workLocationid;
                $contractModel->save();

                $jobData = $workOrderModel->careerOpportunity;
                Rateshelper::calculateContractEstimates($contractModel,$workOrderModel,$jobData);

                $workOrderModel->onboard_change_start_date = Carbon::createFromFormat('m/d/Y', $request->startDate)->format('Y-m-d');
                $workOrderModel->onboard_changed_end_date = Carbon::createFromFormat('m/d/Y', $request->endDate)->format('Y-m-d');
                $workOrderModel->original_start_date = Carbon::createFromFormat('m/d/Y', $request->originalStartDate)->format('Y-m-d');
                $workOrderModel->sourcing_type = $request->candidateSourcing;
                $workOrderModel->on_board_status = 1;
                $workOrderModel->save();

                $submissionModel = $workOrderModel->submission;
                $submissionModel->resume_status = 8;
                $submissionModel->save();

                $contractRate = new ContractRate();

                $contractRate->contract_id = $contractModel->id;
                $contractRate->workorder_id = $workOrderModel->id;
                $contractRate->client_bill_rate = $workOrderModel->wo_bill_rate;
                $contractRate->client_overtime_rate = $workOrderModel->wo_client_over_time;
                $contractRate->client_doubletime_rate = $workOrderModel->wo_client_double_time;
                $contractRate->candidate_pay_rate = $workOrderModel->wo_pay_rate;
                $contractRate->candidate_overtime_rate = $workOrderModel->wo_over_time;
                $contractRate->candidate_doubletime_rate = $workOrderModel->wo_double_time;
                $contractRate->vendor_bill_rate = $workOrderModel->vendor_bill_rate;
                $contractRate->vendor_overtime_rate = $workOrderModel->vendor_overtime_rate;
                $contractRate->vendor_doubletime_rate = $workOrderModel->vendor_doubletime_rate;
                $contractRate->effective_date = $workOrderModel->onboard_change_start_date;

                $contractRate->save();
                $costCenters = CareerOpportunitiesBu::where('career_opportunity_id',$contractModel->career_opportunity_id)->get();
                foreach($costCenters as $costCenter){
                    $timesheetModel = new TimesheetProject();
                    $timesheetModel->contract_id = $contractModel->id;
                    $timesheetModel->bu_id = $costCenter->bu_unit;
                    $timesheetModel->budget_percentage = $costCenter->percentage;
                    $timesheetModel->billrate = $workOrderModel->wo_bill_rate;
                    $timesheetModel->overtime_billrate = $workOrderModel->wo_client_over_time;
                    $timesheetModel->doubletime_billrate = $workOrderModel->wo_client_double_time;

                    $timesheetModel->payrate = $workOrderModel->wo_pay_rate;
                    $timesheetModel->overtime_payrate = $workOrderModel->wo_over_time;
                    $timesheetModel->doubletime_payrate = $workOrderModel->wo_double_time;

                    $timesheetModel->vendor_billrate = $workOrderModel->vendor_bill_rate;
                    $timesheetModel->vendor_overtime_billrate = $workOrderModel->vendor_overtime_rate;
                    $timesheetModel->vendor_doubletime_billrate = $workOrderModel->vendor_doubletime_rate;

                    $timesheetModel->effective_date = $workOrderModel->onboard_change_start_date;

                    $timesheetModel->save();
                }
                $contractCount = CareerOpportunitiesContract::where('career_opportunity_id', $workOrderModel->career_opportunity_id)->count();


                    // if all job openigs are filled then the status should be filled and they cannot do more offer etc.
                    if($contractCount == $jobData->num_openings || $contractCount > $jobData->num_openings){
                        $jobData->rejected_type = 1;
                        $jobData->reason_for_rejection = 2297;
                        $jobData->rejected_by = Admin::getAdminIdByUserId(Auth::id());
                        $jobData->date_rejected = now();
                        $jobData->jobStatus = 4;
                        $jobData->save();
                        $type = 'Filled';
                       updateSubmission($jobData,$type);
                    }

                session()->flash('success', 'Contractor Login information has been emailed!');
                return response()->json([
                    'success' => true,
                    'message' => 'Contractor Login information has been emailed!',
                    'redirect_url' => route('admin.contracts.index') // Redirect back URL for AJAX
                ]);


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd($id);
        $contract = CareerOpportunitiesContract::findOrFail($id);
        return view('admin.contract.view', compact('contract'));
    }
    public function saveComments(Request $request) //SAVENOTES
    {
     //   dd($request->all());
        $request->validate([
            'note' => 'required|string',
            'contract_id' => 'required|integer'
        ]);
        $note = new ContractNote();
        $note->contract_id = $request->contract_id;
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
            'redirect_url' => route('admin.contracts.show', $note->contract_id) // Redirect back URL for AJAX
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contract = CareerOpportunitiesContract::with([
            'careerOpportunity',
            'extensionRequest',
            'ContractAdditionalBudgetRequest',
        ])->findOrFail($id);
        return view('admin.contract.contract_update', compact('contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($request);
        $contractId = $request->contractId;
        $contract = CareerOpportunitiesContract::with('careerOpportunity')->findOrFail($contractId);
        $postType = $request->selectedOption;
        // Fetch related data only once
        $workorder = CareerOpportunitiesWorkorder::findOrFail($contract->workorder_id);
        $offer = CareerOpportunitiesOffer::findOrFail($contract->offer_id);
        $job = CareerOpportunity::findOrFail($contract->career_opportunity_id);
        $candidate = Consultant::findOrFail($contract->candidate_id);
        // Fetch old data only once
        $contractOld = CareerOpportunitiesContract::with('careerOpportunity')->findOrFail($contractId);
        $workorderOld = CareerOpportunitiesWorkorder::findOrFail($contract->workorder_id);
        $offerOld = CareerOpportunitiesOffer::findOrFail($contract->offer_id);
        $jobOld = CareerOpportunity::findOrFail($contract->career_opportunity_id);
        $candidateOld = Consultant::findOrFail($contract->candidate_id);
        $successMessage = '';
        switch ($postType) {
            case '1': // Additional Budget Update
                $notes = $request->additional_budget_notes;
                $editHist = $this->createContractEditHistory($contract, $workorderOld, $candidateOld, $jobOld, $offerOld, $contractOld, $postType, $notes);
                $additionbudget = $this->additionBudgetUpdateData($contract, $workorder, $request, $editHist);
                if ($additionbudget !== true) {
                    // Return validation error if it's a JSON response
                    return $additionbudget;
                }
                $successMessage = 'Contract Additional Budget updated successfully!';
                break;

            case '2': // Contract Extension Request
                $notes = $request->extension_reason_notes;
                $editHist = $this->createContractEditHistory($contract, $workorderOld, $candidateOld, $jobOld, $offerOld, $contractOld, $postType, $notes);
                $extension = $this->ContractExtensionReq($request->all(), $workorder, $job, $contract, $editHist);

                if ($extension !== true) {
                    // Return validation error if it's a JSON response
                    return $extension;
                }
                $successMessage = 'Contract Extension added successfully!';
                break;

            case '3': // Contract Rate Change Request
                $editHist = $this->createContractEditHistory($contract, $workorderOld, $candidateOld, $jobOld, $offerOld, $contractOld, $postType, '');
                $rate = $this->contractRateChangeRequest($request->all(), $workorder, $contract, $editHist);
                if ($rate !== true) {
                    // Return validation error if it's a JSON response
                    return $rate;
                }
                $successMessage = 'Contract Rate Change Request processed successfully!';
                break;

            case '4': // Non-financial Contract Update
                $notes = '';
                $editHist = $this->createContractEditHistory($contract, $workorderOld, $candidateOld, $jobOld, $offerOld, $contractOld, $postType, $notes);
                $nonfinancial = $this->nonFinancialupdateData($contract, $request);
                if ($nonfinancial !== true) {
                    // Return validation error if it's a JSON response
                    return $nonfinancial;
                }
                $successMessage = 'Non-financial contract update processed successfully!';
                break;

            case '5': // Contract Date Update
                $notes = '';
                $editHist = $this->createContractEditHistory($contract, $workorderOld, $candidateOld, $jobOld, $offerOld, $contractOld, $postType, $notes);
                $dateupdate = $this->contractDateUpdate($contract, $request);
                if ($dateupdate !== true) {
                    // Return validation error if it's a JSON response
                    return $dateupdate;
                }
                $successMessage = 'Contract Date updated successfully!';
                break;

            case '6': // Contract Termination
                $termination = $this->ContractTermination($contract, $request);
                if ($termination !== true) {
                    return $termination; // Validation error
                }
                $successMessage = 'Contract terminated successfully!';
                break;

            default:
                return response()->json(['message' => 'No update made'], 400);
        }
        session()->flash('success', $successMessage);
        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => route('admin.contracts.show', $contractId),
        ]);
    }


    public function ContractTermination($contract,$request) {
        $validator = Validator::make($request->all(), [
            'termination_date' => 'required|date_format:m/d/Y',
            'termination_notes' => 'required|string',
            'termination_reason' => 'required',
            'termination_feedback' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        $terminationDate = Carbon::createFromFormat('m/d/Y', $request->termination_date)->format('Y-m-d');

        $contract->update([
            'termination_date' => $terminationDate,
            'termination_reason' => $request->termination_reason,
            'termination_notes' => $request->termination_notes,
            'termination_feedback' => $request->termination_feedback,
            'termination_status' => 2,
            'status' => 6,
            'termination_date' => now(),
            'term_by_id' =>Admin::getAdminIdByUserId(auth()->id()),
            'term_by_type' =>1,
        ]); 
            
        return true;

    }

    public function ContractDateUpdate($contract,$request) {
      
        $validator = Validator::make($request->all(), [
            'new_contract_start_date' => 'required|date_format:m/d/Y',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        $startDate = Carbon::createFromFormat('m/d/Y', $request->new_contract_start_date)->format('Y-m-d');

        $contract->update([
            'start_date' => $startDate,
        ]); 
            if ($contract->workOrder) { 
               
                $contract->workorder->onboard_change_start_date =$startDate;
                if(strtotime($contract->workorder->end_date) >= strtotime(date('Y-m-d'))){
                    $contract->workorder->status = 1;
                }
                if($contract->workorder->save()){
                        // Find the ContractRate record
                        $contractRate = ContractRate::where('contract_id', $contract->id)->first();

                        if ($contractRate && Carbon::parse($startDate)->lt(Carbon::parse($contractRate->effective_date))) {
                            // Update the effective_date field
                            $contractRate->effective_date = $startDate;
                            $contractRate->save();
                        }

                        // Find all TimesheetProject records for the contract
                        $timesheetProjects = TimesheetProject::where('contract_id', $contract->id)->get();

                        foreach ($timesheetProjects as $timesheetProject) {
                            if (Carbon::parse($startDate)->lt(Carbon::parse($timesheetProject->effective_date))) {
                                // Update the effective_date field
                                $timesheetProject->effective_date = $startDate;
                                $timesheetProject->save();
                            }
                        }

                        Rateshelper::calculateContractEstimates($contract,$contract->workorder,$contract->careerOpportunity);

                       
                }
            
            }
            return true;

    }

    public function nonFinancialupdateData($contract,$request)
    {
            $validator = Validator::make($request->all(), [
            'businessjustification' => 'required',
            'expensesallowed' => 'required',
            'timesheet' => 'required',
            'hiringmanager' => 'required',
            'worklocation' => 'required',
            'vendoraccountmanager' => 'required',
            'contractorportal' => 'required',
            'originalstartdate' => ['required'],
            'locationTax' => 'required',
            'candidatesourcetype' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        //         $contract->update([
        //     'hiring_manager_id' => $validatedData['hiringmanager'],
        //     'location_id' => $validatedData['worklocation'],
        // ]);
        if ($contract->workOrder) {
            $contract->workOrder->update([

                'original_start_date' => Carbon::createFromFormat('m/d/Y', $validatedData['originalstartdate'])->format('Y-m-d'),
                'approval_manager' => $validatedData['timesheet'],
                'hiring_manager_id' =>$validatedData['hiringmanager'],
                'location_id' =>$validatedData['worklocation'],
                'location_tax' => $validatedData['locationTax'],
                'expenses_allowed' => $validatedData['expensesallowed'],
                'job_level_notes' => $validatedData['businessjustification'],
                'sourcing_type' => $validatedData['candidatesourcetype'],
            ]);
        }
        if ($contract->submission) {
            $contract->submission->update([
                'emp_msp_account_mngr' => $validatedData['vendoraccountmanager'],
            ]);
        }
        if ($contract->workOrder->consultant) {
                $contract->workOrder->consultant->update([
                    'candidate_id' => $validatedData['contractorportal'],
                ]);
            }

        return true;
    }
    public function additionBudgetUpdateData($contract,$workorder,$request,$editHist)
    {
            $validator = Validator::make($request->all(), [
            'additional_budget_reason' => ['required'],
            'amount' => 'required|string',
            'additional_budget_notes' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
        $contractAdditionalBudget = new ContractAdditionalBudget();
        $contractAdditionalBudget->user_id = Admin::getAdminIdByUserId(auth()->id());
        $contractAdditionalBudget->created_by = 1;
        $contractAdditionalBudget->created_by_type = 1; 
        $contractAdditionalBudget->history_id = $editHist->id; 
        $contractAdditionalBudget->contract_id = $contract->id;
        $contractAdditionalBudget->amount = $validatedData['amount'];
        $contractAdditionalBudget->notes = $validatedData['additional_budget_notes'];
        $contractAdditionalBudget->additional_budget_reason = $validatedData['additional_budget_reason'];
        $contractAdditionalBudget->status = 'Pending';
        $contractAdditionalBudget->save();
        if ($contractAdditionalBudget->save()) {
        contractHelper::createContractBudgetWorkflow($contractAdditionalBudget, $contract);
        return true;
            }
            else{
                return false;
            }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function createContractEditHistory($contract,$workorderOld,$candidateOld,$jobOld,$offerOld,$contractOld,$postType,$notes){

     
        
        

        $data = new ContractEditHistory;
        $data->created_by = Admin::getAdminIdByUserId(\Auth::id());
        $data->created_from = 1;
        $data->contract_id = $contract->id;
        $data->job_level = $workorderOld->job_level;
        $data->job_level_notes = $notes;
        $data->candidate_id = $contract->candidate_id;
        $data->bill_rate = $workorderOld->wo_bill_rate;
        $data->pay_rate = $workorderOld->wo_pay_rate;
        $data->contractor_overtimerate = $workorderOld->wo_over_time;
        $data->client_overtimerate = $workorderOld->wo_client_over_time;
        $data->contractor_doubletimerate = $workorderOld->wo_double_time;
        $data->client_doubletimerate = $workorderOld->wo_client_double_time;
        $data->vendor_bill_rate = $workorderOld->vendor_bill_rate;
        $data->vendor_overtime_rate = $workorderOld->vendor_overtime_rate;
        $data->vendor_doubletime_rate = $workorderOld->vendor_doubletime_rate;
        $data->hiring_manager = $workorderOld->hiring_manager_id;
        //$data->vendor_account_manager = $old_account_manager;
        $data->sales_tax = $workorderOld->sales_tax;
        $data->county_tax = $workorderOld->county_tax;
        $data->location_tax = $workorderOld->location_tax;
        $data->markup = $workorderOld->markup;
        $data->total_estimated_cost = $contract->total_estimated_cost;
        $data->location = $offerOld->location_id;
        $data->timesheet_approval_manager = $workorderOld->approval_manager;
        $data->start_date = $contractOld->start_date;
        $data->end_date = $contractOld->end_date;
        $data->effective_date = $contractOld->effective_date;
        $data->gl_code_id = $workorderOld->gl_code_id;
        $data->group_number = $workorderOld->group_number_id;
        $data->acc_unit = $workorderOld->acc_unit;
        $data->cost_center = $workorderOld->wo_cost_center;
        $data->sub_company = $workorderOld->sub_company;
        $data->location_identifier = $workorderOld->location_identifier;
        $data->job_type = $workorderOld->job_type;
        $data->hire_type = $workorderOld->hire_type;
        $data->bu_number = $workorderOld->bu_number;
        $data->department_code = $workorderOld->department_code;
        $data->candidate_type = $workorderOld->candidate_type;
        $data->fieldglass_id = $workorderOld->fieldglass_id;
        $data->workday_position_id = $workorderOld->workday_position_id;
        $data->job_id = $jobOld->id;
        $data->category_id = $jobOld->cat_id;
        $data->sourcing_type = $workorderOld->sourcing_type;
        $data->msp_fees = $workorderOld->msp_per;
        $data->job_brand = $workorderOld->job_brand;
        $data->po_number = $workorderOld->po_number_id;
        $data->bcbs_lan_id = $workorderOld->bcbs_lan_id;
        $data->timesheet_method = $workorderOld->timesheet_method;
        $data->resume = $candidateOld->resume;
        $data->candidate_portal_id = $candidateOld->candidate_ID;
        $data->offer_digital_doc = $offerOld->offer_digital_doc;
        $data->offer_second_digital_doc = $offerOld->offer_second_digital_doc;
        $data->budget_manager = $workorderOld->budget_manager;
        $data->original_start_date = $workorderOld->original_start_date;
        $data->created_at = date('Y-m-d H:i:s');
        $data->updated_history_screen =  'Other';
        $data->contract_update_type =  $postType;
        if($data->save()){
            return $data;
        }
    }

    public function ContractExtensionReq($post, $workorder, $job, $contract, $editHist){
        $model = new ContractExtensionRequest;
        $billRate = str_replace(",", "",$post['bill_rate']);
        $clientOverTime = str_replace(",", "",$post['client_overtime_bill_rate']);
        $clientDoubleTime = str_replace(",", "",$post['client_doubletime_bill_rate']);

        $vendorBillRate=$billRate-($billRate * ($workorder->markup/100));
        $vendorOvertimeRate =$clientOverTime-($clientOverTime*($workorder->markup/100));
        $vendorDoubleRate=$clientDoubleTime-($clientDoubleTime*($workorder->markup/100));

        $model->history_id =  $editHist->id;
        $model->created_by =   Admin::getAdminIdByUserId(\Auth::id());
        $model->created_by_type =  1;
        $model->reason_of_extension =  $post['extension_reason'];
        $model->note_of_extension =  $post['additional_budget_notes'];
       // $model->hr_approver = $post['hr_approver'];
        
       // $fromdateFormat = ListingUtility::phpdateFormatByUserId(Yii::app()->user->id,'msp');

        $model->new_contract_end_date = date('Y-m-d', strtotime($post['extension_date']));
        $model->new_contract_start_date = $contract->end_date;
         
        if(Rateshelper::checkSowStatus($workorder->id)) {
            $model->bill_rate =  $billRate;
            $model->overtime_billrate = $clientOverTime;
            $model->doubletime_billrate =  $clientDoubleTime;

            $model->pay_rate = str_replace(",", "",$post['pay_rate']);
            $model->overtime_payrate = str_replace(',','',$post['client_overtime_bill_rate']);
            $model->doubletime_payrate =str_replace(',','',$post['client_doubletime_bill_rate']);

            $working_days = Rateshelper::number_of_working_days($model->new_contract_start_date,$model->new_contract_end_date);
            $hoursPerWeek = $job->hours_per_week;
            $hoursPerDay = $job->hours_per_day;
            $daysPerWeek = $job->day_per_week;

            $estimatedExtBudget = Rateshelper::estimateWithPaymentType($working_days,$billRate,$job);
            $model->new_estimate_cost =  $estimatedExtBudget;

            $model->vendor_bill_rate =  $vendorBillRate;
            $model->vendor_overtime_billrate =  $vendorOvertimeRate;
            $model->vendor_doubletime_billrate =  $vendorDoubleRate;
        }
        $model->contract_id =  $contract->id;
        $model->ext_status = 1;
        $model->ext_vendor_approval = 0;

        if($model->save()){
            if($workorder->contract_type != 2) {
                contractHelper::contractExtensionWorkflowProcess($model,  $contract);
            }else{
                $model->ext_status = 2;
                $model->ext_vendor_approval = 2;
                $model->cronjob_status = 1;
                $model->cronjob_date_time = date("Y-m-d H:i:s");
                $model->save(false);

                $contract->status = 1;
                $contract->end_date = date('Y-m-d h:i:s', strtotime($model->new_contract_end_date));
                $contract->save(false);

                $workorder->workorder_status = 1;
                $workorder->onboard_changed_end_date = date('Y-m-d h:i:s', strtotime($model->new_contract_end_date));
                $workorder->save(false);

            }
        }
        return true;

    }

    public function contractRateChangeRequest($postValues, $workorder, $contract, $editHist){
       $jobModel = CareerOpportunity::find($contract->career_opportunity_id);

        if(Rateshelper::checkSowStatus($workorder->id)) {
            $billRate = str_replace(",", "",$postValues['bill_rate']);
            $clientOverTime = str_replace(",", "",$postValues['client_overtime_bill_rate']);
            $clientDoubleTime = str_replace(",", "",$postValues['client_doubletime_bill_rate']);

            $payRate = str_replace(",", "",$postValues['pay_rate']);
            $consultantOT = str_replace(',','',$postValues['contractor_overtime_pay_rate']);
            $consultantDT =  str_replace(',','',$postValues['contractor_double_time_rate']);

            $vendorBillRate = $billRate - ($billRate * ($workorder->msp_per/100));
            $vendorOvertimeRate = $clientOverTime - ($clientOverTime * ($workorder->msp_per/100));
            $vendorDoubleRate = $clientDoubleTime - ($clientDoubleTime * ($workorder->msp_per/100));

            $working_days = Rateshelper::number_of_working_days($contract->start_date,$contract->end_date);
            $total_estimated_cost = Rateshelper::estimateWithPaymentType($working_days,$billRate,$jobModel);

        } else {
            $billRate = 0;
            $clientOverTime = 0;
            $clientDoubleTime = 0;
            $payRate = 0;
            $consultantOT = 0;
            $consultantDT = 0;
            $vendorBillRate = 0;
            $vendorOvertimeRate = 0;
            $vendorDoubleRate = 0;
            $total_estimated_cost = 0 ;
        }

        

        $model = new ContractRateEditRequest;
        $model->contract_id = $contract->id;
        $model->created_by = Admin::getAdminIdByUserId(\Auth::id());
        $model->created_by_type= 1;
        $model->bill_rate =  $billRate;
        $model->pay_rate =  $payRate;
        $model->candidate_overtime_payrate =  $consultantOT;
        $model->client_overtime_payrate =  $clientOverTime;
        $model->candidate_doubletime_payrate =  $consultantDT;
        $model->client_doubletime_payrate =  $clientDoubleTime;
        $model->vendor_bill_rate = $vendorBillRate;
        $model->vendor_overtime_rate = $vendorOvertimeRate;
        $model->vendor_doubletime_rate = $vendorDoubleRate;
        $model->start_date = $contract->start_date;
        $model->end_date = $contract->end_date;
        $model->status = 0;
        $model->request_notes= '';
        $model->effective_date= date('Y-m-d',strtotime($postValues['effective_date']));
        //$model->location_tax= ''; //$postValues['location_tax'];
        $model->impacted_timesheet_ids=''; //$postValues['impacted_timesheets'];
        $model->markup= $postValues['markup'];
        $model->history_id=$editHist->id;
        $model->total_estimated_cost= $total_estimated_cost;
        if($model->save()) {

            
            $currentRates = Rateshelper::returnContractEffectiveRate($contract->id);
            // if($billRate <= $currentRates['bill_rate'] /*||  !ListingUtility::checkSowStatus($workorder->id)*/){
            //     $model->status = 3;
            //     $model->save();
                
            // }else{
                contractHelper::contractEditRatesWorkflowProcess($model,  $contract);
            // }
        }
        return true;
    }
    // ratechange 
    
    public function quickcreate(){
        
        $pwt_jobs = CareerOpportunity::whereHas('workerType', function ($query) {
            $query->where('id', 11);
        })->get();
        
        return view('admin.contract.quickcreate',['pwt_jobs' => $pwt_jobs,]);
    }

    public function quickContractStore(Request $request){
        $validatedData = $request->validate([
            'jobProfile' => 'required|integer',
            'hireManager' => 'required|string',
            'hireManagerId' => 'required|integer',
            'workLocation' => 'required|integer',
            'vendor' => 'required|integer',
            'subDate' => 'required|date_format:Y-m-d',
            'offStartDate' => 'required|date_format:Y-m-d',
            'offEndDate' => 'required|date_format:Y-m-d',
            'newExist' => 'required|integer',
            'phyLocation' => 'required|integer',
            'candidateFirstName' => 'required_if:newExist,1|nullable|string',
            'candidateMiddleName' => 'nullable|string',
            'candidateLastName' => 'required_if:newExist,1|nullable|string',
            'candidatePhone' => 'required_if:newExist,1|nullable|string',
            'candidateEmail' => 'required_if:newExist,1|nullable|email',
            'existingCandidate' => 'required_if:newExist,2|nullable|integer',
        ]);
        
        $job = CareerOpportunity::find($validatedData['jobProfile']);
        if (!$job) {
            Log::error('Job profile not found for ID: ' . $validatedData['jobProfile']);
            return redirect()->back()->withErrors(['jobProfile' => 'Job not found.']);
        }

        // Process the data and create the contract
        try {
            // Create or update candidate
            if ($validatedData['newExist'] == 1) {
                $candidate = $this->createCandidate($validatedData);
            } else {
                $candidate = $this->fetchExistingCandidate($validatedData['existingCandidate']);
            }

            // Create submission, offer, and work order
            $submission = $this->createSubmission($validatedData, $job, $candidate);
            $offer = $this->createOffer($validatedData, $job, $candidate, $submission);
            $workOrder = $this->createWorkOrder($validatedData, $job, $candidate, $submission, $offer);

            $contract = CareerOpportunitiesContract::create([
                'career_opportunity_id' => $job->id,
                'candidate_id' => $candidate->id,
                'submission_id' => $submission->id,
                'offer_id' => $offer->id,
                'workorder_id' => $workOrder->id,
                'status' => 1,
                'start_date' => $validatedData['offStartDate'],
                'end_date' => $validatedData['offEndDate'],
                'hiring_manager_id' => $validatedData['hireManagerId'],
                'location_id' => $validatedData['workLocation'],
                'created_by' => Admin::getAdminIdByUserId(\Auth::id()),
                'vendor_id' => $validatedData['vendor'], 
                'created_by_type' => 1,
                
            ]);

            session()->flash('success', 'Contract created!');
            return response()->json([
                'success' => true,
                'message' => 'Contract created!',
                'redirect_url' => route('admin.contracts.index') // Redirect back URL for AJAX
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the contract.',
                'error' => $e->getMessage() // Optional: Include the error message for debugging
            ], 500);
        }
    }

    private function createCandidate($data)
    {
        try {
            return DB::transaction(function () use ($data) {
                // Check if user already exists
                if (User::where('email', $data['candidateEmail'])->exists()) {
                    throw new \Exception('A user with this email already exists.');
                }

                // Create the User
                $user = User::create([
                    'name' => trim($data['candidateFirstName'] . ' ' . $data['candidateLastName']),
                    'email' => $data['candidateEmail'],
                    'password' => Hash::make('password'), 
                ]);
                $user->is_consultant = 1;
                $user->save();

                // Create the Consultant
                return Consultant::create([
                    'first_name' => $data['candidateFirstName'],
                    'middle_name' => $data['candidateMiddleName'],
                    'last_name' => $data['candidateLastName'],
                    'phone' => $data['candidatePhone'],
                    'user_id' => $user->id, 
                    'profile_status' => 1,
                    'dob' => '1990-11-01',
                    'unique_id' => generateUniqueUserCode(),
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Error creating candidate', ['exception' => $e]);
            throw $e;
        }
    }

    private function fetchExistingCandidate($candidateId)
    {
        // Logic to fetch an existing candidate
        return Consultant::findOrFail($candidateId);
    }

    private function createSubmission($data, $job, $candidate)
    {
        // Logic to create a submission
        return CareerOpportunitySubmission::create([
            'career_opportunity_id' => $job->id,
            'candidate_id' => $candidate->id,
            'resume_status' => 9,
            'estimate_start_date' => $data['subDate'],
            'vendor_id' => $data['vendor'],
            'created_by_user' => Admin::getAdminIdByUserId(\Auth::id()),
            'location_id' => $data['workLocation'],
            'markup' => 0,
            'actuall_markup' => 0,
            'vendor_bill_rate' => 0,
            'candidate_pay_rate' => 0,
            'bill_rate' => 0,
            'over_time_rate' => 0,
            'client_over_time_rate' => 0,
            'double_time_rate' => 0,
            'client_double_time_rate' => 0,
            
            // Add other fields as needed
        ]);
    }

    private function createOffer($data, $job, $candidate, $submission)
    {
        // Logic to create an offer
        return CareerOpportunitiesOffer::create([
            'career_opportunity_id' => $job->id,
            'candidate_id' => $candidate->id,
            'submission_id' => $submission->id,
            'status' => 1,
            'start_date' => $data['offStartDate'],
            'end_date' => $data['offEndDate'],
            'hiring_manager_id' => $data['hireManagerId'],
            'vendor_id' => $data['vendor'],
            'location_id' => $data['workLocation'],
            // Add other fields as needed
        ]);
    }

    private function createWorkOrder($data, $job, $candidate, $submission, $offer)
    {
        // Logic to create a work order
        return CareerOpportunitiesWorkorder::create([
            'career_opportunity_id' => $job->id,
            'candidate_id' => $candidate->id,
            'submission_id' => $submission->id,
            'offer_id' => $offer->id,
            'status' => 1,
            'vendor_id' => $data['vendor'],
            'location_id' => $data['workLocation'],
            'hiring_manager_id' => $data['hireManagerId'],
            'approval_manager' => $data['hireManagerId'],
            'job_level' => $job->job_level,
            'division_id' => $job->division_id,
            'created_by_type' => 1,
            'created_by_id' => Admin::getAdminIdByUserId(\Auth::id()),
            'start_date' => $data['offStartDate'],
            'end_date' => $data['offEndDate'],
            // Add other fields as needed
        ]);
    }

    

    public function getHiringManager($id)
    {
        $jobProfile = CareerOpportunity::find($id);
        
        if ($jobProfile) {
            return response()->json([
                'success' => true,
                'hiringManager' => $jobProfile->hiringManager
            ]);
        }

        return response()->json(['success' => false]);
    }


}
