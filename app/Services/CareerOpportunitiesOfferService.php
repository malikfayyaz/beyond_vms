<?php
namespace App\Services;

use App\Models\Workflow;
use App\Models\OfferWorkFlow;

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

        foreach ($workflows as $wf) {
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
            ];
        }

        // Insert all records at once using batch insert
        OfferWorkFlow::insert($workflowData);

        return true;
    }
}
