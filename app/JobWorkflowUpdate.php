<?php 
namespace App;
use App\Models\Workflow;
use App\Models\JobWorkFlow;
use Illuminate\Http\Request;


class JobWorkflowUpdate
{
    public static function createJobWorkflow($job)
    {
        // dd($job);
        $workflow = Workflow::where('client_id', 1)->get();
        $count = 0;
        foreach($workflow as $wf){
        	$jobWF = new JobWorkFlow;
        	$jobWF->job_id = $job->id;
            $jobWF->client_id = $wf->hiring_manager_id;
            $jobWF->workflow_id = 0;
            $jobWF->costcenter_id = 0;
            $jobWF->approval_role_id = $wf->approval_role_id; 
            $jobWF->bulk_approval = 0;
            $jobWF->approval_number =$wf->approval_number;
            $jobWF->status = 'Pending';
            $jobWF->status_time = date('Y-m-d h:i:s');
            $jobWF->approval_required = $wf->approval_required;
            // $jobWF->approved_datetime = '';
            // $jobWF->rejection_id = '';
            // $jobWF->rejection_reason = '';
            // $jobWF->approve_reject_by = '';
            // $jobWF->approve_reject_type = '';
            // $jobWF->approve_reject_from = '';
            // $jobWF->ip_address = request()->ip();
            // $jobWF->machine_user_name = '';
            // $jobWF->approval_doc = '';
            // $jobWF->approval_notes = '';
            $jobWF->save();
        }
        //dd('testing testing ');
        // get all workflow with hiring manager from workflows table
        // update all in new table of workflows 
        // 

    }
}


?>
