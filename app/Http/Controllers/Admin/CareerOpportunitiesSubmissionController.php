<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesOffer;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesSubmissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $submissions = CareerOpportunitySubmission::with(['consultant','vendor','careerOpportunity.hiringManager','location'])
                ->get();
            return DataTables::of($submissions)
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('unique_id', function($row) {
                    return $row->consultant ? $row->consultant->unique_id : 'N/A';
                })
                ->addColumn('resume_status', function($row) {
                    return CareerOpportunitySubmission::getSubmissionStatus($row->resume_status);
                })
                ->addColumn('hiring_manager_name', function($row) {
                    // Access the hiring manager through the careerOpportunity relationship
                    return $row->careerOpportunity && $row->careerOpportunity->hiringManager
                        ? $row->careerOpportunity->hiringManager->full_name
                        : 'N/A';
                })
                ->addColumn('location_name', function($row) {
                    return $row->location->name; // Access the attribute
                })
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('career_opportunity_title', function($row) {
                    return $row->careerOpportunity ? $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' : 'N/A';
                })
                ->addColumn('worker_type', function ($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                    ? $row->careerOpportunity->workerType->title
                    : 'N/A';
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('admin.submission.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->make(true);
        }
        return view('admin.submission.index');
    }
    public function show($id)
    {
        $submission = CareerOpportunitySubmission::findOrFail($id);
        $offer = CareerOpportunitiesOffer::where('submission_id', $submission->id)
        ->orderBy('id', 'DESC')
        ->first();
        // Return a view or other response with the submission details
        return view('admin.submission.view', compact('submission','offer'));
    }
    public function rejectCandidate(Request $request)
    {
                  $request->validate([
            'submissionId' => 'required|exists:career_opportunities_submission,id', // Adjust table name if necessary
            // 'rejectedBy' => 'required|exists:users,id', // Assuming you have a users table
        ]);
        $user = Admin::getAdminIdByUserId(Auth::id());
        $careersubmission = CareerOpportunitySubmission::find($request->submissionId);
        $careersubmission->rejected_type = '1';
        $careersubmission->rejected_by = $user;
        $careersubmission->resume_status = 6;
        $careersubmission->note_for_rejection = '';
        $careersubmission->reason_for_rejection = '';
        $careersubmission->date_rejected =now(); //Rejected Date time.
        $careersubmission->save(); // Save the changes
        session()->flash('success', 'Submission Rejected successfully!');
        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.submission.show', $request->submissionId),
            'message' => 'Submission Rejected successfully!'
        ]);
    }
    public function shortlistCandidate(Request $request)
    {
        $request->validate([
            'submissionId' => 'required|exists:career_opportunities_submission,id', // Adjust table name if necessary
        ]);
        $careersubmission = CareerOpportunitySubmission::find($request->submissionId);
        $careersubmission->resume_status = 3;
        $careersubmission->release_to_client = 1;
        $careersubmission->shortlisted_date = now();
        $careersubmission->save(); // Save the changes
        session()->flash('success', 'Submission Shortlisted successfully!');
        return response()->json([
            'success' => true,
            'redirect_url' => route('admin.submission.show', $careersubmission->id),
            'message' => 'Submission Shortlisted successfully!'
        ]);
    }


    public function jobSubmission(Request $request){
        dd($request->all());
    }
}
