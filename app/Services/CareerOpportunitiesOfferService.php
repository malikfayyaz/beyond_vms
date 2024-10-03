<?php
namespace App\Services;

use App\Models\CareerOpportunitiesOffer;
use App\Models\Workflow;
use App\Models\OfferWorkFlow;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\Consultant;


class CareerOpportunitiesOfferService
{
    public static function createOfferWorkflow($offer)
    {
        // Fetch all workflows for the hiring manager of the offer
        $workflows = Workflow::where('client_id', $offer->careerOpportunity->hiring_manager)->get();
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
                'offer_id' => $offer->id,
                'client_id' => $wf->hiring_manager_id,
                'workflow_id' => 0,
                'costcenter_id' => 0,
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
        OfferWorkFlow::insert($workflowData);

        return true;
    }

    public static function approveofferWorkFlow($request){
        $user = \Auth::user();
        $userid = \Auth::id();
        
        $sessionrole = session('selected_role');
        if($sessionrole == "Admin") {
            $userid =  Admin::getAdminIdByUserId($userid);
        }elseif($sessionrole == "Client"){
            $userid =  Client::getClientIdByUserId($userid);
        }elseif($sessionrole == "Vendor"){
            $userid =  Vendor::getVendorIdByUserId($userid);
        }elseif($sessionrole == "Consultant"){
            $userid =  Consultant::getConsultantIdByUserId($userid);
        }
        $portal = 'Portal';
        $workflow = OfferWorkFlow::findOrFail($request->rowId);
      
        self::acceptOfferWorkFlow($request->rowId, $userid, $sessionrole, $portal,$request);
        $nextWorkflow = OfferWorkFlow::where([
            ['offer_id', '=', $workflow->offer_id],
            ['status', '=', 'Pending'],
            ['email_sent', '=', 0]
        ])
            ->orderBy('id')
            ->get();
        // write query to get all the pending records 
        
        $count = 0;
        if(count($nextWorkFlow)){
            foreach($nextWorkFlow as $workflow){
                if($count == 0){
                    if($workflow->approval_required == 0 ){ // Just Approve this Record as no Approval Required
                        self::acceptOfferWorkFlow($workflow->id, $userid, $sessionrole, $portal,$request);
                    }else{
                        $workflow->email_sent = 1;
                        $workflow->save();
                        // Mail send code will be added here
                        $count++;
                    }
                }
            }
        }else{
            $offer = CareerOpportunitiesOffer::findOrFail($workflow->offer_id);
            $offer->status = '4';
            $offer->save();
        }
    }

    protected static function acceptOfferWorkFlow($workflowid, $userid, $role, $portal, $request){
       
        $filename = handleFileUpload($request, 'jobAttachment', 'offer_workflow_attachments');
        $workflow = OfferWorkFlow::findOrFail($workflowid);
        $workflow->status = 'Approved'; // Update the status to approved
        $workflow->approval_notes = $request->note;
        $jobWorkFlow->approve_reject_by = $userid;
        $jobWorkFlow->approve_reject_type = $role;
        $workflow->approval_doc = $filename;
        $workflow->approved_datetime = now();
        $jobWorkFlow->approve_reject_from = $portal;
        $jobWorkFlow->ip_address = $request->ip();
        $jobWorkFlow->machine_user_name = gethostname();
        $workflow->save();
       
    }
}
