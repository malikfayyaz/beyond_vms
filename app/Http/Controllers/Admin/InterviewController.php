<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesInterview;


class InterviewController extends Controller
{
    public function create($id)
    {
        $submission =  CareerOpportunitySubmission::findOrFail($id);

        return view('admin.interview.create', compact('submission'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'eventName' => 'required|string|max:255',
            'interviewDuration' => 'required|integer',
            'timeZone' => 'required|integer',
            'startDate' => 'required|date',
            'location' => 'nullable|integer',
            'remote' => 'required|integer',
            'interviewInstructions' => 'nullable|string',
            'members' => 'nullable',
            'otherDate1' => 'nullable|date',
            'otherDate2' => 'nullable|date',
            'otherDate3' => 'nullable|date',
        ]);

        $submission = CareerOpportunitySubmission::findOrFail($request->submissionid);
        
        $mapedData = [
            "submission_id" =>$submission->id,
            "candidate_id" =>$submission->candidate_id,
            "career_opportunity_id" =>$submission->career_opportunity_id,
            "event_name" =>$validatedData['eventName'],
            "interview_duration" =>$validatedData['interviewDuration'],
            "time_zone" =>$validatedData['timeZone'],
            "interview_type" =>$validatedData['remote'],
            "recommended_date" =>$validatedData['startDate'],
            "other_date_1" =>$validatedData['otherDate1'],
            "other_date_2" =>$validatedData['otherDate2'],
            "other_date_3" =>$validatedData['otherDate3'],
            "location_id" =>$validatedData['location'],
            "interview_instructions" =>$validatedData['interviewInstructions'],
            "interview_members" =>$validatedData['members'],
        ];

        $InterviewCreate = CareerOpportunitiesInterview::create( $mapedData );

        session()->flash('success', 'Interview saved successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Interview saved successfully!',
            'redirect_url' => route('admin.offer.index') // Redirect back URL for AJAX
        ]);
    }
}
