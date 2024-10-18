<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CareerOpportunitiesInterview;

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
        dd("candi");
    }
}
