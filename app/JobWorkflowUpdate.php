<?php 
namespace App;
use App\Models\Workflow;
use App\Models\JobWorkFlow;
use App\Models\Setting;
use App\Models\CareerOpportunity;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\Consultant;


class JobWorkflowUpdate
{
    public static function createJobWorkflow($job)
    {
        $workflow = Workflow::where('client_id', $job['hiring_manager'])->get();
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
            if($count == 0){
                $jobWF->email_sent = 1;
            }else{
                $jobWF->email_sent = 0;
            }
            $jobWF->approval_required = $wf->approval_required;
            $jobWF->save();
            $count++;
        }
    }

    public static function approveJobWorkFlow($request){

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        if ($sessionrole == "Admin") {
            $userid = Admin::getAdminIdByUserId($userid);
        } elseif ($sessionrole == "Client") {
            $userid = Client::getClientIdByUserId($userid);
        } elseif ($sessionrole == "Vendor") {
            $userid = Vendor::getVendorIdByUserId($userid);
        } elseif ($sessionrole == "Consultant") {
            $userid = Consultant::getConsultantIdByUserId($userid);
        }
        
        $portal = 'Portal';

        $jobWorkFlow = JobWorkFlow::find($request->workflow_id);
        self::acceptJobWorkFlow($request->workflow_id, $userid, $sessionrole, $portal,$request);

        // write query to get all the pending records 
        $nextWorkFlow = JobWorkFlow::where('job_id', '=', $jobWorkFlow->job_id)->Where('status','=', 'Pending')->orderby('approval_number', 'ASC')->get();
        $count = 0;
        if(count($nextWorkFlow)){
            foreach($nextWorkFlow as $workflow){
                if($count == 0){
                    if($workflow->approval_required == 'no' ){ // Just Approve this Record as no Approval Required
                        self::acceptJobWorkFlow($workflow->id, $userid, $sessionrole, $portal,$request);
                    }else{
                        $workflow->email_sent = 1;
                        $workflow->save();
                        // Mail send code will be added here
                        $count++;
                    }
                }
            }
        }else{
            $job = CareerOpportunity::find($jobWorkFlow->job_id);
            $job->jobstatus = 22;
            $job->save();
        }
    }

    protected static function acceptJobWorkFlow($workflowid, $userid, $role, $portal, $request){
        $jobWorkFlow = JobWorkFlow::find($workflowid);
        $jobWorkFlow->status  = 'Approved';
        $jobWorkFlow->approved_datetime = date('Y-m-d h:i:s');
        $jobWorkFlow->approve_reject_by = $userid;
        $jobWorkFlow->approve_reject_type = $role;
        $jobWorkFlow->approve_reject_from = $portal;
        $jobWorkFlow->ip_address = $request->ip();
        $jobWorkFlow->machine_user_name = gethostname();
        // dd($request->all());

        if(isset($request->jobAttachment)){
            $file = $request->file('jobAttachment');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('jobWorkFlow', $fileName, 'public'); 
        }else{
            $fileName = '';
        }
        $jobWorkFlow->approval_doc = $fileName;
        $jobWorkFlow->approval_notes = $request->note;
        $jobWorkFlow->save();
    }

    public static function rejectJobWorkFlow($request){
        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');

        if ($sessionrole == "Admin") {
            $userid = Admin::getAdminIdByUserId($userid);
        } elseif ($sessionrole == "Client") {
            $userid = Client::getClientIdByUserId($userid);
        } elseif ($sessionrole == "Vendor") {
            $userid = Vendor::getVendorIdByUserId($userid);
        } elseif ($sessionrole == "Consultant") {
            $userid = Consultant::getConsultantIdByUserId($userid);
        }
        $portal = 'Portal';

        $jobWorkFlow = JobWorkFlow::find($request->workflow_id);
        self::rejectWorkFlow($request->workflow_id, $userid, $sessionrole, $portal,$request);

        $job = CareerOpportunity::find($jobWorkFlow->job_id);
        $job->jobstatus = 5;
        $job->rejected_by = $userid;
        $job->rejected_type = $sessionrole;
        $job->reason_for_rejection = $request->reason;
        $job->note_for_rejection = $request->note;
        $job->date_rejected = date('Y-m-d h:i:s');
        $job->save();
    }

    protected static function rejectWorkFlow($workflowid, $userid, $role, $portal, $request){
        $jobWorkFlow = JobWorkFlow::find($workflowid);
        $setting = Setting::find($request->reason);
        $jobWorkFlow->status  = 'Rejected';
        $jobWorkFlow->approved_datetime = date('Y-m-d h:i:s');
        $jobWorkFlow->approve_reject_by = $userid;
        $jobWorkFlow->approve_reject_type = $role;
        $jobWorkFlow->approve_reject_from = $portal;

        $jobWorkFlow->rejection_id = $request->reason;
        $jobWorkFlow->rejection_reason = $setting->title;
        
        $jobWorkFlow->ip_address = $request->ip();
        $jobWorkFlow->machine_user_name = gethostname();
       // $jobWorkFlow->approval_doc = (isset($request->attachment)) ? $request->attachment : '';
        $jobWorkFlow->approval_notes = $request->note;
        $jobWorkFlow->save();
    }


}


?>
