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
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunity;
use App\Models\ContractNote;
use App\Models\OfferWorkFlow;
use App\Models\CareerOpportunitiesContract;
use App\Models\contractAdditionalBudget;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\ContractRate;
use App\Models\TimesheetProject;
use App\Models\CareerOpportunitiesBu;
class CareerOpportunitiesContractController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $adminId = Admin::getAdminIdByUserId(Auth::id());
            $data = CareerOpportunitiesContract::with('hiringManager','careerOpportunity','workOrder.vendor','location');
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
                ->addColumn('career_opportunity', function($row) {
                    return $row->careerOpportunity ? $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' : 'N/A';
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
                ->rawColumns(['action'])
                ->make(true);
        }
        // Logic to get and display catalog items
        return view('admin.contract.index'); // Assumes you have a corresponding Blade view
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
        $contract = CareerOpportunitiesContract::with('careerOpportunity')->findOrFail($id);
        $job = CareerOpportunitiesContract::with('careerOpportunity')->findOrFail($id);
        return view('admin.contract.view', compact('contract','job'));
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
        $contract = CareerOpportunitiesContract::findOrFail($id);
        return view('admin.contract.contract_update', compact('contract'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contractId = $request->contractId;
        $contract = CareerOpportunitiesContract::with('careerOpportunity')->findOrFail($contractId);
       if ($request->selectedOption == '1') {
        $additionbudget = $this->additionBudgetUpdateData($contract,$request);
        if ($additionbudget !== true) {
            // Return validation error if it's a JSON response
            return $additionbudget;
        }
        session()->flash('success', 'Contract Aditional Budget updated successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Contract Aditional Budget updated successfully!',
                    'redirect_url' => route('admin.contracts.show', $contractId)
                ]);
       }

       if ($request->selectedOption == '4') {
        $nonfinancial = $this->nonFinancialupdateData($contract,$request);
        if ($nonfinancial !== true) {
            // Return validation error if it's a JSON response
            return $nonfinancial;
        }
        session()->flash('success', 'Contract updated successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Contract updated successfully!',
                    'redirect_url' => route('admin.contracts.show', $contractId)
                ]);
        }
        if ($request->selectedOption =='5') {
            $dateupdate = $this->ContractDateUpdate($contract,$request); 
            if ($dateupdate !== true) {
                // Return validation error if it's a JSON response
                return $dateupdate;
            }
            session()->flash('success', 'Contract updated successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Contract updated successfully!',
                    'redirect_url' => route('admin.contracts.show', $contractId)
                ]);
        }

        if ($request->selectedOption =='6') {
            $termination = $this->ContractTermination($contract,$request); 
            if ($termination !== true) {
                // Return validation error if it's a JSON response
                return $termination;
            }
            session()->flash('success', 'Contract updated successfully!');
                return response()->json([
                    'success' => true,
                    'message' => 'Contract updated successfully!',
                    'redirect_url' => route('admin.contracts.show', $contractId)
                ]);
        }

    // If selectedOption is not '4', return an appropriate response
    return response()->json(['message' => 'No update made'], 400);
    }

    public function ContractTermination($contract,$request) {
        $validator = Validator::make($request->all(), [
            'termination_date' => 'required|date_format:m/d/Y',
            'termination_notes' => 'required|string',
            'termination_reason' => 'required',
            'termination_can_feedback' => 'required',
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
            'termination_feedback' => $request->termination_can_feedback,
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
            'businessjustification' => 'required|string',
            'expensesallowed' => 'required|string',
            'timesheet' => 'required',
            'hiringmanager' => 'required',
            'worklocation' => 'required|string',
            'vendoraccountmanager' => 'required|string',
            'contractorportal' => 'required|string',
            'originalstartdate' => ['required'],
            'locationTax' => 'required|string',
            'candidatesourcetype' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $validatedData = $validator->validated();
                $contract->update([
            'hiring_manager_id' => $validatedData['hiringmanager'],
            'location_id' => $validatedData['worklocation'],
        ]);
        if ($contract->workOrder) {
            $contract->workOrder->update([

                'original_start_date' => Carbon::createFromFormat('m/d/Y', $validatedData['originalstartdate'])->format('Y/m/d'),
                'approval_manager' => $validatedData['timesheet'],
                'location_tax' => $validatedData['locationTax'],
                'expenses_allowed' => $validatedData['expensesallowed'],
                'job_level_notes' => $validatedData['businessjustification'],
                'sourcing_type' => $validatedData['candidatesourcetype'],
            ]);
        }
        if ($contract->submission) {
            $contract->submission->update([
                'vendor_id' => $validatedData['vendoraccountmanager'],
            ]);
        }
        if ($contract->workOrder->consultant) {
                $contract->workOrder->consultant->update([
                    'candidate_id' => $validatedData['contractorportal'],
                ]);
            }

        return true;
    }
    public function additionBudgetUpdateData($contract,$request)
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
        $contractAdditionalBudget->contract_id = $contract->id;
        $contractAdditionalBudget->amount = $validatedData['amount'];
        $contractAdditionalBudget->notes = $validatedData['additional_budget_notes'];
        $contractAdditionalBudget->additional_budget_reason = $validatedData['additional_budget_reason'];
        $contractAdditionalBudget->status = 'Pending';
        $contractAdditionalBudget->save();
        if ($contractAdditionalBudget->save()) {
        contractHelper::createContractSpendWorkflowProcess($contractAdditionalBudget, $contract);
        return true;
            }else{
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
}
