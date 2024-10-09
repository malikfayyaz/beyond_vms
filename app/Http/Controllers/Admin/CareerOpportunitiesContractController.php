<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\CareerOpportunitiesContract;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\Admin;
use App\Models\ContractRate;
use App\Models\TimesheetProject;
use App\Models\CareerOpportunitiesBu;

class CareerOpportunitiesContractController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
                calculateContractEstimates($contractModel,$workOrderModel,$jobData);

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

                session()->flash('success', 'Contractor Login information has been emailed!');
                return response()->json([
                    'success' => true,
                    'message' => 'Contractor Login information has been emailed!',
                    // 'redirect_url' => route('admin.career-opportunities.index') // Redirect back URL for AJAX
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
