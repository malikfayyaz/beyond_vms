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
     /*public function updateAdditionalWorkflow($request){
        if(isset($request)){
            $contract = CareerOpportunitiesContract::with([
            'careerOpportunity',
            'contractAdditionalBudgetRequest',
            ])->findOrFail($request->contractId);
            $additionalBudgetRequest = $contract->contractAdditionalBudgetRequest()
            ->where('id', $request->rowId)
            ->firstOrFail();

            WorkflowProcess::contractSpendWorkflowProcess($model, $model->id, $contract_id,$workorder->wo_hiring_manager);

            Yii::app()->user->setFlash('success', '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                               Workflow updated successfully.</div>');
            $this->redirect(array('contractView','id'=>$contract_id));

        }
    }*/

}
