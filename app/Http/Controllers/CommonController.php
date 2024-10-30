<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareerOpportunitiesInterview;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesContract;
use App\Models\CareerOpportunity;

class CommonController extends Controller
{
    public function rejectInterview(Request $request,$id) 
    {
        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        
        if ($sessionrole === 'admin') {
           $role = 1;
        } else if ($sessionrole === 'client') {
            $role = 2;
        } else if ($sessionrole === 'vendor') {
            $role = 3;
        }

        $user_id =  checkUserId($userid,$sessionrole);

        $validateData = $request->validate([
            'reschedule_reason' => 'required|int',
            'rejection_note' => 'required|string|max:250',
        ]);

        $interview = CareerOpportunitiesInterview::findOrFail($id);

        $interview->reason_rejection = $validateData['reschedule_reason'];
        $interview->notes = $validateData['rejection_note'];
        $interview->interview_acceptance_date = null; 
        $interview->acceptance_notes = null; 
        $interview->status = 3;
        $interview->rejected_by = $user_id;       
        $interview->rejected_type = $role; 
        $interview->interview_cancellation_date = now();
        $interview->save();

        $successMessage = 'Interview rejected successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.interview.index")  // Redirect URL for AJAX
        ]);
    }

    public function rejectCandidate(Request $request,$id) 
    { 
        $validateData = $request->validate([
            'cand_rej_reason' => 'required|int',
            'cand_rej_note' => 'required|string|max:250',
        ]);

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        
        if ($sessionrole === 'admin') {
           $role = 1;
        } else if ($sessionrole === 'client') {
            $role = 2;
        } else if ($sessionrole === 'vendor') {
            $role = 3;
        }

        $user_id =  checkUserId($userid,$sessionrole);
        
        $interview = CareerOpportunitiesInterview::findOrFail($id);
        $submission = CareerOpportunitySubmission::findOrFail($interview->submission_id);

        $interview->reason_rejection = $validateData['cand_rej_reason'];
        $interview->notes = $validateData['cand_rej_note'];
        $interview->interview_acceptance_date = null; 
        $interview->acceptance_notes = null; 
        $interview->status = 3;
        $interview->rejected_by = $user_id;       
        $interview->rejected_type = $role; 
        $interview->interview_cancellation_date = now();
        $interview->save();

        $submission->reason_for_rejection = $validateData['cand_rej_reason'];
        $submission->note_for_rejection = $validateData['cand_rej_note'];
        $submission->resume_status = 6;
        $submission->rejected_by = $user_id;
        $submission->rejected_type = $role;
        $submission->date_rejected = now();
        $submission->save();

        $successMessage = 'Interview & candidate rejected successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.interview.index")  // Redirect URL for AJAX
        ]);
    }

    public function closeAssignmentTemp(Request $request,$id) 
    {
        $validateData = $request->validate([
            'close_contr_reason' => 'required|int',
            'close_contr_note' => 'required|string|max:250',
        ]);

        $user = \Auth::user();
        $userid = \Auth::id();
        $sessionrole = session('selected_role');
        
        if ($sessionrole === 'admin') {
           $role = 1;
        } else if ($sessionrole === 'client') {
            $role = 2;
        } else if ($sessionrole === 'vendor') {
            $role = 3;
        }

        $user_id =  checkUserId($userid,$sessionrole);
        // dd($validateData);
        
        $contract = CareerOpportunitiesContract::findOrFail($id);
        $CareerOpportunity = CareerOpportunity::findOrFail($contract->career_opportunity_id);

        $contract->status = 3;
        $contract->termination_status = 2;
        $contract->termination_reason = $validateData['close_contr_reason'];
        $contract->termination_notes = $validateData['close_contr_note'];
        $contract->term_by_id = $user_id;
        $contract->term_by_type = $role;
        $contract->termination_date = now();
        $contract->save();

        $successMessage = 'Contract terminated successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.contracts.show", $contract->id)  // Redirect URL for AJAX
        ]);
    }
    
    public function openContract(Request $request,$id) 
    {
        $contract = CareerOpportunitiesContract::findOrFail($id);
        $sessionrole = session('selected_role');

        $contract->status = 1;
        $contract->termination_status = 0;
        $contract->termination_reason = null;
        $contract->termination_notes = null;
        $contract->term_by_id = null;
        $contract->term_by_type = null;
        $contract->termination_date = null;

        $contract->save();

        $successMessage = 'Contract open-back successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' =>  route("$sessionrole.contracts.show", $contract->id)
        ]);
        
    }

    public function jobFlyout($id)
    {
        $job = CareerOpportunity::with('hiringManager', 'workerType')
        ->withCount('submissions')
        ->findOrFail($id);

        return response()->json($job);
    }
}
