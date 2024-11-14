<?php
namespace App\Services;

use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitiesContract;
use App\Models\CareerOpportunity;
use App\Models\Setting;
use App\Models\Workflow;
use App\Models\ContractBudgetWorkflow;
use App\Models\ContractRatesEditWorkflow;
use App\Models\ContractExtensionWorkflow;
use App\Models\ContractRate;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\Consultant;


class CareerOpportunitiesContractService
{
    public static function createContractBudgetWorkflow($contractAdditionalBudget,$contract)
    {
        // Fetch all workflows for the hiring manager of the offer
        $workflows = Workflow::where('client_id', $contract->workorder->hiring_manager_id)->get();
        // dd($workflows);
        // If there are no workflows, return early
        if ($workflows->isEmpty()) {
            return false;
        }
        // Prepare the data to insert all at once
        $workflowData = [];
        $i = 0;
        foreach ($workflows as $wf) {
            $emailSentValue = 0;
            if ($i == 0){
                $emailSentValue = 1;
            }
            $workflowData[] = [
                'contract_id' => $contract->id,
                'client_id' => $wf->hiring_manager_id,
                'workflow_id' => 0,
                'request_id' => 0,
                'approval_role_id' => $wf->approval_role_id,
                'bulk_approval' => 0,
                'approval_number' => $wf->approval_number,
                'status' => 'Pending',
                'status_time' => now(),
                'approval_required' => $wf->approval_required == 'yes' ? 1 : 0,
                'email_sent' => $emailSentValue,
            ];
            $i++;
        }

        // Insert all records at once using batch insert
        ContractBudgetWorkflow::insert($workflowData);

        return true;
    }

    public static function approveContractBudgetWorkflow($request){
        $user = \Auth::user();
        $userid = \Auth::id();

        $sessionrole = session('selected_role');
        $userid =  checkUserId($userid,$sessionrole);
        $portal = 'Portal';
        $workflow = ContractBudgetWorkflow::findOrFail($request->rowId);
        self::acceptContractBudgetWorkflow($request->rowId, $userid, $sessionrole, $portal,$request);
        $nextWorkflow = ContractBudgetWorkflow::where([
            ['contract_id', '=', $workflow->contract_id],
            ['status', '=', 'Pending'],
            ['email_sent', '=', 0]
        ])
            ->orderBy('id')
            ->get();
        // write query to get all the pending records

        $count = 0;
        if(count($nextWorkflow)>0){
            foreach($nextWorkflow as $workflow){
                if($count == 0){
                    if($workflow->approval_required == 0 ){ // Just Approve this Record as no Approval Required
                        self::acceptContractBudgetWorkflow($workflow->id, $userid, $sessionrole, $portal,$request);
                    }else{
                        $workflow->email_sent = 1;
                        $workflow->save();
                        // Mail send code will be added here
                        $count++;
                    }
                }
            }
        }else{
            $contract = CareerOpportunitiesContract::findOrFail($request->contractId);
            $offer = CareerOpportunitiesOffer::findOrFail($contract->offer_id);
            $offer->status = '4'; //offer status 4 is for pending vendor approval
            $offer->save();
        }
    }

    protected static function acceptContractBudgetWorkflow($workflowid, $userid, $role, $portal, $request){

        $filename = handleFileUpload($request, 'jobAttachment', 'contract_budget_workflow_attachments');
        $workflow = ContractBudgetWorkflow::findOrFail($workflowid);
        $workflow->status = 'Approved'; // Update the status to approved
        $workflow->approval_notes = $request->note;
        $workflow->approve_reject_by = $userid;
        $workflow->approve_reject_type = $role;
        $workflow->approval_doc = $filename;
        $workflow->approved_datetime = now();
        $workflow->approve_reject_from = $portal;
        $workflow->ip_address = $request->ip();
        $workflow->machine_user_name = gethostname();
        $workflow->save();

    }
    public static function rejectcontractsWorkFlow($request) {

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');

        $userid =  checkUserId($userid,$sessionrole);

        $portal = 'Portal';
        $workflow = ContractBudgetWorkflow::findOrFail($request->rowId);
        self::rejectContractWorkFlow($request->rowId, $userid, $sessionrole, $portal, $request);
        $nextWorkflow = ContractBudgetWorkflow::where([
            ['contract_id', '=', $workflow->contract_id],
            ['status', '=', 'Pending'],
            ['email_sent', '=', 0]
        ])
            ->orderBy('id')
            ->get();
        $count = 0;
        if(count($nextWorkflow)>0){
            foreach($nextWorkflow as $workflow){
                if($count == 0){
                    if($workflow->approval_required == 0 ){ 
                        self::acceptContractBudgetWorkflow($workflow->id, $userid, $sessionrole, $portal,$request);
                    }else{
                        $workflow->email_sent = 1;
                        $workflow->save();
                        // Mail send code will be added here
                        $count++;
                    }
                }
            }
        }else{
            $contract = CareerOpportunitiesContract::findOrFail($request->contractId);
            $offer = CareerOpportunitiesOffer::findOrFail($contract->offer_id);
            $offer->status = '2'; //offer status 2 is for rejected
            $offer->save();
        }
    }

    protected static function rejectContractWorkFlow($workflowid, $userid, $role, $portal, $request) {
        $workflow = ContractBudgetWorkflow::findOrFail($workflowid);
        $workflow->status = 'Rejected'; // Update the status to Rejected
        $workflow->rejection_reason = $request->reason;
        $workflow->approval_notes = $request->note;
        $workflow->approve_reject_by = $userid;
        $workflow->approve_reject_type = $role;
        $workflow->approved_datetime = now();
        $workflow->approve_reject_from = $portal;
        $workflow->ip_address = $request->ip();
        $workflow->machine_user_name = gethostname();
        $workflow->save();
    }

    // rate contract workflow 

    public static function contractEditRatesWorkflowProcess($model,$contract)
    {
        // Fetch all workflows for the hiring manager of the offer
        $workflows = Workflow::where('client_id', $contract->workorder->hiring_manager_id)->get();
        // dd($workflows);
        // If there are no workflows, return early
        if ($workflows->isEmpty()) {
            return false;
        }
        // Prepare the data to insert all at once
        $workflowData = [];
        $i = 0;
        foreach ($workflows as $wf) {
            $emailSentValue = 0;
            if ($i == 0){
                $emailSentValue = 1;
            }
            $workflowData[] = [
                'contract_id' => $contract->id,
                'client_id' => $wf->hiring_manager_id,
                'workflow_id' => 0,
                'request_id' => $model->id,
                'approval_role_id' => $wf->approval_role_id,
                'bulk_approval' => 0,
                'approval_number' => $wf->approval_number,
                'status' => 'Pending',
                'status_time' => now(),
                'approval_required' => $wf->approval_required == 'yes' ? 1 : 0,
                'email_sent' => $emailSentValue,
            ];
            $i++;
        }

        // Insert all records at once using batch insert
        ContractRatesEditWorkflow::insert($workflowData);

        return true;
    } 

    // extension workflow
 public static function contractExtensionWorkflowProcess($model,$contract)
     {
         // Fetch all workflows for the hiring manager of the offer
         $workflows = Workflow::where('client_id', $contract->workorder->hiring_manager_id)->get();
         // dd($workflows);
         // If there are no workflows, return early
         if ($workflows->isEmpty()) {
             return false;
         }
         // Prepare the data to insert all at once
         $workflowData = [];
         $i = 0;
         foreach ($workflows as $wf) {
             $emailSentValue = 0;
             if ($i == 0){
                 $emailSentValue = 1;
             }
             $workflowData[] = [
                 'contract_id' => $contract->id,
                 'client_id' => $wf->hiring_manager_id,
                 'workflow_id' => 0,
                 'request_id' => $model->id,
                 'approval_role_id' => $wf->approval_role_id,
                 'bulk_approval' => 0,
                 'approval_number' => $wf->approval_number,
                 'status' => 'Pending',
                 'status_time' => now(),
                 'approval_required' => $wf->approval_required == 'yes' ? 1 : 0,
                 'email_sent' => $emailSentValue,
             ];
             $i++;
         }
 
         // Insert all records at once using batch insert
         ContractExtensionWorkflow::insert($workflowData);
 
         return true;
     }

     public static function contractExtensionWorkflowApprove($request)
     {
        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        $userid =  checkUserId($userid,$sessionrole);
        $portal = 'Portal';
        $contract = CareerOpportunitiesContract::findOrFail($request->contractId);
        $workflow = ContractExtensionWorkflow::findOrFail($request->rowId);
        if($request->actionType == 'Accept'){
        self::acceptContractExtWorkflow($request->rowId, $userid, $sessionrole, $portal,$request);
        }
        if($request->actionType == 'Reject'){
        self::rejectContractExtWorkflow($request->rowId, $userid, $sessionrole, $portal,$request);
        }
        $nextWorkflow = ContractExtensionWorkflow::where([
            ['contract_id', '=', $workflow->contract_id],
            ['status', '=', 'Pending'],
            ['email_sent', '=', 0]
        ])
            ->orderBy('id')
            ->get();
        $count = 0;
        if(count($nextWorkflow)>0){
            foreach($nextWorkflow as $workflow){
                if($count == 0){
                    if($workflow->approval_required == 0 ){
                      if ($request->actionType == 'Accept') {
                        self::acceptContractExtWorkflow($workflow->id, $userid, $sessionrole, $portal, $request);
                    } else {
                        self::rejectContractExtWorkflow($workflow->id, $userid, $sessionrole, $portal, $request);
                            }
                    }else{
                        $workflow->email_sent = 1;
                        $workflow->save();
                        $count++;
                    }
                }
            }
        } 
         return true;
     }
    protected static function acceptContractExtWorkflow($workflowid, $userid, $role, $portal, $request){
        $workflow = ContractExtensionWorkflow::findOrFail($request->rowId);
        $filename = handleFileUpload($request, 'contractAttachment', 'contract_extensio_workflow_attachments');
        $workflow->status = 'Approved';
        $workflow->approval_notes = $request->note;
        $workflow->approval_doc = $filename;
        $workflow->approve_reject_by = $userid;
        $workflow->approve_reject_type = $role;
        $workflow->approved_datetime = now();
        $workflow->approve_reject_from = $portal;
        $workflow->ip_address = $request->ip();
        $workflow->machine_user_name = gethostname();
        $workflow->save();
    }
    protected static function rejectContractExtWorkflow($workflowid, $userid, $role, $portal, $request){
        $workflow = ContractExtensionWorkflow::findOrFail($request->rowId);
        $filename = handleFileUpload($request, 'contractAttachment', 'contract_extensio_workflow_attachments');
        $workflow->status = 'Rejected';
        $workflow->rejection_id = $request->reason;
        $workflow->rejection_reason = $request->note;
        $workflow->approval_notes = $request->note;
        $workflow->approval_doc = $filename;
        $workflow->approve_reject_by = $userid;
        $workflow->approve_reject_type = $role;
        $workflow->approved_datetime = now();
        $workflow->approve_reject_from = $portal;
        $workflow->ip_address = $request->ip();
        $workflow->machine_user_name = gethostname();
        $workflow->save();
        $otherMembers = ContractExtensionWorkflow::where([
            ['request_id', '=', $workflow->request_id],
            ['status', '=', 'Pending']
        ])
            ->orderBy('id')
            ->get();
        foreach ($otherMembers as $otherMember){
            $otherMember->status = 'Rejected';
            $otherMember->save();
        }
    }

    public static function approveCRateWorkflow($workflow,$request_record,$request,$portal,$approved_by,$appRejType){
      
        self::acceptCRateWorkflow($workflow, $approved_by, $appRejType, $portal,$request);
        $nextWorkflow = ContractBudgetWorkflow::where([
            ['contract_id', '=', $workflow->contract_id],
            ['status', '=', 'Pending'],
            ['email_sent', '=', 0]
        ])
            ->orderBy('id')
            ->get();
        // write query to get all the pending records

        $count = 0;
        if(count($nextWorkflow)>0){
            foreach($nextWorkflow as $workflow){
                if($count == 0){
                    if($workflow->approval_required == 0 ){ // Just Approve this Record as no Approval Required
                        self::acceptCRateWorkflow($workflow, $approved_by, $appRejType, $portal,$request);
                    }else{
                        $workflow->email_sent = 1;
                        $workflow->save();
                        // Mail send code will be added here
                        $count++;
                    }
                }
            }
        }else{
            $request_record->status = 1;
            $request_record->save(); 
            $workorder =  $request_record->contract->workorder;
            $workorder->wo_pay_rate = $request_record->pay_rate;
            $workorder->wo_over_time = $request_record->candidate_overtime_payrate;
            $workorder->wo_double_time = $request_record->candidate_doubletime_payrate;
            $workorder->wo_bill_rate = $request_record->bill_rate;
            $workorder->wo_client_over_time = $request_record->client_overtime_payrate;
            $workorder->wo_client_double_time = $request_record->client_doubletime_payrate;
            $workorder->vendor_bill_rate = $request_record->vendor_bill_rate;
            $workorder->vendor_overtime_rate = $request_record->vendor_overtime_rate;
            $workorder->vendor_doubletime_rate = $request_record->vendor_doubletime_rate;
            $workorder->markup = $request_record->markup;
            $workorder->save();

            $contract =  $request_record->contract;
            $contract->start_date = $request_record->start_date;
            $contract->end_date = $request_record->end_date;
            $contract->status = 1;
            $contract->save();

            $contractRate = new ContractRate;
            $contractRate->contract_id = $contract->id;
            $contractRate->workorder_id = $workorder->id;
            $contractRate->client_bill_rate = $request_record->bill_rate;
            $contractRate->client_overtime_rate = $request_record->client_overtime_payrate;
            $contractRate->client_doubletime_rate = $request_record->client_doubletime_payrate;
            $contractRate->candidate_pay_rate = $request_record->pay_rate;
            $contractRate->candidate_overtime_rate = $request_record->candidate_overtime_payrate;
            $contractRate->candidate_doubletime_rate = $request_record->candidate_doubletime_payrate;
            $contractRate->vendor_bill_rate =  $request_record->vendor_bill_rate;
            $contractRate->markup = $request_record->markup;
            $contractRate->vendor_overtime_rate = $request_record->vendor_overtime_rate;
            $contractRate->vendor_doubletime_rate = $request_record->vendor_doubletime_rate;
            $contractRate->effective_date = $request_record->effective_date;
            $contractRate->request_type = 1;
            $contractRate->history_id = $request_record->history_id;
            $contractRate->date_created = now();
            $contractRate->save();
        }
    }

    protected static function acceptCRateWorkflow($workflow, $userid, $role, $portal, $request){

        $filename = handleFileUpload($request, 'jobAttachment', 'contract_budget_workflow_attachments');
        $workflow->status = 'Approved'; // Update the status to approved
        $workflow->approval_notes = $request->note;
        $workflow->approve_reject_by = $userid;
        $workflow->approve_reject_type = $role;
        $workflow->approval_doc = $filename;
        $workflow->approved_datetime = now();
        $workflow->approve_reject_from = $portal;
        $workflow->ip_address = $request->ip();
        $workflow->machine_user_name = gethostname();
        $workflow->save();

    }

    public static function RejectCRateWorkflow($approval,$request,$portal,$approved_by,$appRejType){
        $approval->status = 'Rejected';
        $approval->approve_reject_by = $approved_by;
        $approval->rejection_id = $request->reason;
        $approval->ip_address =$request->ip();
        $approval->status_time = now();
        $approval->approve_reject_type = $appRejType;
        $approval->approve_reject_from = $portal;
        $approval->machine_user_name = gethostname();
        $approval->save();

        //other persons record also will be rejected.
        $otherApproval = ContractRatesEditWorkflow::where([
            ['request_id', '=', $approval->request_id],
            ['status', '=', 'Pending']
        ])
            ->orderBy('id')
            ->get();
        if(!empty($otherApproval)){
            foreach ($otherApproval as $item){
                $item->status = 'Rejected';
                $item->save();
            }
        }
    }

}
