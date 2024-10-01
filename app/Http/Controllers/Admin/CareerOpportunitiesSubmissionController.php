<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesSubmissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $submissions = CareerOpportunitySubmission::with(['consultant','vendor','careerOpportunity.hiringManager','location'])->get();

            return DataTables::of($submissions)
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('unique_id', function($row) {
                    return $row->consultant ? $row->consultant->unique_id : 'N/A';
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

        // Return a view or other response with the submission details
        return view('admin.submission.view', compact('submission'));
    }
    public function rejectCandidate(Request $request)
    {
/*        $request->validate([
            'submissionId' => 'required|exists:submissions,id', // Adjust table name if necessary
            'rejectedBy' => 'required|exists:users,id', // Assuming you have a users table
        ]);*/
        $user = Auth::user();
        $careersubmission = CareerOpportunitySubmission::find($request->submissionId);
        $oldStatus = $careersubmission->resume_status;
        $careersubmission->rejected_type = '1';
        $careersubmission->rejected_by = $user->id;
        $careersubmission->resume_status = 6;
        $careersubmission->note_for_rejection = '';
        $careersubmission->reason_for_rejection = '';
        $careersubmission->date_rejected = date('Y-m-d H:i:s'); //Rejected Date time.
        $careersubmission->save(); // Save the changes

        return response()->json(['success' => true]);
    }

}
