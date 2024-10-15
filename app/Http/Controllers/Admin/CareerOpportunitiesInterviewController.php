<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitiesInterview;
use App\Models\CareerOpportunitiesInterviewDate;
use App\Models\CareerOpportunitiesInterviewMember;
use App\Models\Admin;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;


class CareerOpportunitiesInterviewController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $interview = CareerOpportunitiesInterview::with(['consultant', 'careerOpportunity', 'duration', 'timezone', 'interviewtype', 'submission','interviewDates'])
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
            ->addColumn('primary_date', function($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();
                
                return $primaryDate ? $primaryDate->formatted_schedule_date : 'N/A'; 
            })
            ->addColumn('primary_start_time', function($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();
                
                return $primaryDate ? $primaryDate->formatted_start_time : 'N/A'; 
            })
            ->addColumn('primary_end_time', function($row) {
                $primaryDate = $row->interviewDates->where('schedule_date_order', 1)->first();
                
                return $primaryDate ? $primaryDate->formatted_end_time : 'N/A'; 
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
                        </a>
                        <a href="' . route('admin.interview.show', $row->id) . '"
                            class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-eye"></i>
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
            'jobAttachment' => 'nullable|file|mimes:doc,docx,pdf|max:5120',
            'where' => 'required|integer',
            'interviewType' => 'required|integer',
            'interviewInstructions' => 'nullable|string',
            'interview_detail' => 'required|string',
            'interviewMembers' => 'required|string', // Ensure it's an array
            'otherDate1' => 'nullable|date',
            'otherDate2' => 'nullable|date',
            'otherDate3' => 'nullable|date',
            'selectedTimeSlots' => 'required|string',
        ]);


        $interviewMembersArray = explode(',', $validatedData['interviewMembers']);

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

        if ($request->hasFile('jobAttachment')) {
            $imagePath = handleFileUpload($request, 'jobAttachment', 'interview_resume');
            $InterviewCreate->job_attachment = $imagePath;
            $InterviewCreate->save(); // Save after updating the profile image
        }

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

        foreach ($interviewMembersArray as $memberId) {
            // You can use your model to create entries or attach members
            CareerOpportunitiesInterviewMember::create([
                'interview_id' => $InterviewCreate->id, // Replace with your actual interview ID
                'member_id' => $memberId, // Member ID from the array
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
            'jobAttachment' => 'nullable|max:5120',
            'interviewType' => 'required|integer',
            'interview_detail' => 'required|string',
            'interviewInstructions' => 'nullable|string',
            'interviewMembers' => 'nullable',
            'otherDate1' => 'nullable|date',
            'otherDate2' => 'nullable|date',
            'otherDate3' => 'nullable|date',
            'selectedTimeSlots' => 'required|string',
        ]);

        $interviewMembersArray = explode(',', $validatedData['interviewMembers']);


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

        if ($request->hasFile('jobAttachment')) {
            // Delete old image if exists
            $filePath = "interview_resume/". $interview->job_attachment;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // Upload new profile image
            $imagePath = handleFileUpload($request, 'jobAttachment', 'interview_resume');
            if ($imagePath) {
                $interview->update(['job_attachment' => $imagePath]); // Mass assign the image path
            }
        }

        if (empty($request->file('jobAttachment'))) {
            $filePath = "interview_resume/" . $interview->job_attachment;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            $interview->update(['job_attachment' => null]);
        }

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


        // Delete members that are no longer associated
        CareerOpportunitiesInterviewMember::where('interview_id', $interview->id)
            ->whereNotIn('member_id', $interviewMembersArray)
            ->delete();

        // Add or update members
        foreach ($interviewMembersArray as $memberId) {
            // Use firstOrNew to find existing member or create a new one
            $interviewMember = CareerOpportunitiesInterviewMember::firstOrNew([
                'interview_id' => $interview->id,
                'member_id' => $memberId,
            ]);

            // Save the member (this updates if it exists or creates a new entry)
            $interviewMember->save();
        }

        $successMessage = 'Interview updated successfully!';
        session()->flash('success', $successMessage);

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'redirect_url' => route('admin.interview.index')  // Redirect URL for AJAX
        ]);
    }

    public function show($id)
    {
        $interview = CareerOpportunitiesInterview::findOrFail($id);
        $offer = CareerOpportunitiesOffer::where('submission_id', $interview->submission_id)->first();
       
        return view('admin.interview.view', compact('interview','offer'));
    }
}
