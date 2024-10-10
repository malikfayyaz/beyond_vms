<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesInterview;
use App\Models\CareerOpportunitiesInterviewDate;
use App\Models\Admin;
use Yajra\DataTables\Facades\DataTables;


class CareerOpportunitiesInterviewController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $interview = CareerOpportunitiesInterview::with(['consultant', 'careerOpportunity', 'duration', 'timezone', 'interviewtype', 'submission'])
            ->orderBy('id', 'desc') 
            ->get();
            return DataTables::of($interview)
            ->addColumn('type', function($row) {
                return $row->interviewtype ? $row->interviewtype->title : 'N/A';
            }) 
            ->addColumn('consultant_name', function($row) {
                return $row->consultant ? $row->consultant->full_name : 'N/A';
            }) 
            ->addColumn('career_opportunity', function($row) {
                return $row->careerOpportunity ? $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' : 'N/A';
            })
            ->addColumn('hiring_manger', function($row) {
                return $row->careerOpportunity->hiringManager ? $row->careerOpportunity->hiringManager->fullname : 'N/A';
            })
            ->addColumn('vendor_name', function($row) {
                return $row->submission ? $row->submission->vendor->full_name : 'N/A';
            })
            ->addColumn('worker_type', function($row) {
                return $row->careerOpportunity && $row->careerOpportunity->workerType 
                    ? $row->careerOpportunity->workerType->title
                    : 'N/A';
            })
            
            ->addColumn('action', function($row) {
                return '<a href="' . route('admin.interview.edit', $row->id) . '"
                            class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-edit"></i>
                        </a>';
            })
            ->make(true);
        }
        return view('admin.interview.index');
    }

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
            'recommendedDate' => 'required|date',
            'where' => 'nullable|integer',
            'interviewType' => 'required|integer',
            'interviewInstructions' => 'nullable|string',
            'interview_detail' => 'required|string',
            'interviewMembers' => 'nullable',
            'otherDate1' => 'nullable|date',
            'otherDate2' => 'nullable|date',
            'otherDate3' => 'nullable|date',
            'selectedTimeSlots' => 'required|string',
        ]);

        $user = \Auth::user();  
        $userid = \Auth::id();  
        $adminid =  Admin::getAdminIdByUserId($userid); 
        
        $timeSlotsArray = json_decode($validatedData['selectedTimeSlots'], true);

        $timeRange = $timeSlotsArray[$validatedData['recommendedDate']];
        $times = explode(' - ', $timeRange);
        $startTime = $times[0]; 
        $endTime = $times[1]; 

        $startTimeIn24HourFormat = date("H:i:s", strtotime($startTime)); 
        $endTimeIn24HourFormat = date("H:i:s", strtotime($endTime)); 


        $submission = CareerOpportunitySubmission::findOrFail($request->submissionid);
        
        $mapedData = [
            "submission_id" =>$submission->id,
            "candidate_id" =>$submission->candidate_id,
            "career_opportunity_id" =>$submission->career_opportunity_id,
            "event_name" =>$validatedData['eventName'],
            "interview_duration" =>$validatedData['interviewDuration'],
            "time_zone" =>$validatedData['timeZone'],
            "interview_type" =>$validatedData['interviewType'],
            "recommended_date" =>$validatedData['recommendedDate'],
            "other_date_1" =>$validatedData['otherDate1'],
            "other_date_2" =>$validatedData['otherDate2'],
            "other_date_3" =>$validatedData['otherDate3'],
            "location_id" =>$validatedData['where'],
            "interview_instructions" =>$validatedData['interviewInstructions'],
            "interview_members" =>$validatedData['interviewMembers'],
            "start_time" =>$startTimeIn24HourFormat,
            "end_time" =>$endTimeIn24HourFormat,
            "interview_detail" =>$validatedData['interview_detail'],
            "status" => 1,
            "created_by_portal" => 1,
            "created_by" => $adminid,
        ];

        $InterviewCreate = CareerOpportunitiesInterview::create( $mapedData );
        
        $i=1;
        foreach ($timeSlotsArray as $date => $timeSlot) {
            // Split the time slot string into start and end times
            list($startTime, $endTime) = explode(' - ', $timeSlot);
           
            $startTimeIn24HourFormat = date("H:i:s", strtotime($startTime)); 
            $endTimeIn24HourFormat = date("H:i:s", strtotime($endTime)); 
            // Optionally, you can calculate the schedule_date_order based on your logic
            $scheduleDateOrder = $i++;
            CareerOpportunitiesInterviewDate::create([
                'interview_id' => $InterviewCreate->id,        
                'schedule_date' => $date,               
                'start_time' => $startTimeIn24HourFormat,             
                'end_time' => $endTimeIn24HourFormat,                 
                'schedule_date_order' => $scheduleDateOrder, 
            ]);
        }

        session()->flash('success', 'Interview saved successfully!');
        return response()->json([
            'success' => true,
            'message' => 'Interview saved successfully!',
            'redirect_url' => route('admin.interview.index') // Redirect back URL for AJAX
        ]);
    }

    public function edit($id)
    {
        $interview =  CareerOpportunitiesInterview::findOrFail($id);
        $submission =  CareerOpportunitySubmission::findOrFail($interview->submission_id);

        return view('admin.interview.create', compact('interview','submission'))
        ->with(['editMode' => true, 'editIndex' => $id]);
    }

    public function update(Request $request, $id)
    {
        // Validate and update the interview
        $interview = CareerOpportunitiesInterview::findOrFail($id);
        $submission =  CareerOpportunitySubmission::findOrFail($interview->submission_id);
        
        $validatedData = $request->validate([
            'eventName' => 'required|string|max:255',
            'interviewDuration' => 'required|integer',
            'timeZone' => 'required|integer',
            'recommendedDate' => 'required|date',
            'where' => 'nullable|integer',
            'interviewType' => 'required|integer',
            'interview_detail' => 'required|string',
            'interviewInstructions' => 'nullable|string',
            'interviewMembers' => 'nullable',
            'otherDate1' => 'nullable|date',
            'otherDate2' => 'nullable|date',
            'otherDate3' => 'nullable|date',
            'selectedTimeSlots' => 'required|string',
        ]);

        $timeSlotsArray = json_decode($validatedData['selectedTimeSlots'], true);

        $timeRange = $timeSlotsArray[$validatedData['recommendedDate']];
        $times = explode(' - ', $timeRange);
        $startTime = $times[0]; 
        $endTime = $times[1]; 

        $startTimeIn24HourFormat = date("H:i:s", strtotime($startTime)); 
        $endTimeIn24HourFormat = date("H:i:s", strtotime($endTime)); 

        $mapedData = [
            "submission_id" =>$submission->id,
            "candidate_id" =>$submission->candidate_id,
            "career_opportunity_id" =>$submission->career_opportunity_id,
            "event_name" =>$validatedData['eventName'],
            "interview_duration" =>$validatedData['interviewDuration'],
            "time_zone" =>$validatedData['timeZone'],
            "interview_type" =>$validatedData['interviewType'],
            "interview_detail" =>$validatedData['interview_detail'],
            "recommended_date" =>$validatedData['recommendedDate'],
            "other_date_1" =>$validatedData['otherDate1'],
            "other_date_2" =>$validatedData['otherDate2'],
            "other_date_3" =>$validatedData['otherDate3'],
            "location_id" =>$validatedData['where'],
            "interview_instructions" =>$validatedData['interviewInstructions'],
            "interview_members" =>$validatedData['interviewMembers'],
            "start_time" =>$startTimeIn24HourFormat,
            "end_time" =>$endTimeIn24HourFormat,
        ];

        $interview->update($mapedData);

        $incomingDates = array_keys($timeSlotsArray);

        // Delete interview dates that are no longer in the new set of dates
        CareerOpportunitiesInterviewDate::where('interview_id', $interview->id)
            ->whereNotIn('schedule_date', $incomingDates)
            ->delete();

        $i = 1;
        foreach ($timeSlotsArray as $date => $timeSlot) {
            // Split the time slot string into start and end times
            list($startTime, $endTime) = explode(' - ', $timeSlot);
           
            $startTimeIn24HourFormat = date("H:i:s", strtotime($startTime)); 
            $endTimeIn24HourFormat = date("H:i:s", strtotime($endTime)); 
            
            // Optionally, calculate the schedule_date_order based on your logic
            $scheduleDateOrder = $i++;
        
            // Check if the record exists for this interview_id and date
            $interviewDate = CareerOpportunitiesInterviewDate::firstOrNew([
                'interview_id' => $interview->id,
                'schedule_date' => $date,
            ]);
        
            // Update the fields for the existing or new record
            $interviewDate->start_time = $startTimeIn24HourFormat;
            $interviewDate->end_time = $endTimeIn24HourFormat;
            $interviewDate->schedule_date_order = $scheduleDateOrder;
            
            // Save the record (either it updates or creates a new entry)
            $interviewDate->save();
        }

        $successMessage = 'Interview updated successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => route('admin.interview.index')  // Redirect URL for AJAX
        ]);
    }
}
