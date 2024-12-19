<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesOffer;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $counts = [
            'all_subs' => CareerOpportunitySubmission::count(),
            'review' => CareerOpportunitySubmission::where('resume_status', 4)->count(),
            'interview' => CareerOpportunitySubmission::where('resume_status', 5)->count(),
            'rejected' => CareerOpportunitySubmission::where('resume_status', 6)->count(),
            'offer' => CareerOpportunitySubmission::where('resume_status', 7)->count(),
            'hired' => CareerOpportunitySubmission::where('resume_status', 9)->count(),
            'workorder' => CareerOpportunitySubmission::where('resume_status', 11)->count(),
            'withdraw' => CareerOpportunitySubmission::where('resume_status', 12)->count(),
        ];

        if ($request->ajax()) {
            $submissions = CareerOpportunitySubmission::with(['consultant','vendor','careerOpportunity.hiringManager','location'])->latest();

            if ($request->has('type')) {
                $type = $request->input('type');
                switch ($type) {
                    case 'all_subs':
                        break;
                    case 'review':
                        $submissions->where('resume_status', 4);
                        break;
                    case 'interview':
                        $submissions->where('resume_status', 5);
                        break;    
                    case 'rejected':
                        $submissions->where('resume_status', 6);
                        break;
                    case 'offer':
                        $submissions->where('resume_status', 7);
                        break;
                    case 'hired':
                        $submissions->where('resume_status', 9);
                        break;
                    case 'workorder':
                        $submissions->where('resume_status', 11);
                        break;
                    case 'withdraw':
                        $submissions->where('resume_status', 12);
                        break;

                    // Add additional cases as needed
                    default:
                        break; // Show all submissions if no type is specified
                }
            }

            return DataTables::of($submissions)
                ->addColumn('id', function ($row) {
                    return '<span class="submission-detail-trigger text-blue-500 cursor-pointer" data-id="' 
                        . $row->id . '">' . $row->id . '</span>';
                })
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('resume_status', function($row) {
                    return CareerOpportunitySubmission::getSubmissionStatus($row->resume_status);
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
                ->addColumn('career_opportunity_title', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->careerOpportunity->id . '">' . $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' . '</span>';
                })
                ->addColumn('worker_type', function ($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('action', function($row) {
                    return '<a href="' . route('client.submission.show', $row->id) . '"
                                class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                    <i class="fas fa-eye"></i>
                            </a>';
                })
                ->rawColumns(['id','action', 'career_opportunity_title'])
                ->make(true);
        }
        return view('client.submission.index', compact('counts'));
    }

    public function show($id)
    {
        $submission = CareerOpportunitySubmission::findOrFail($id);
        $offer = CareerOpportunitiesOffer::where('submission_id', $submission->id)
        ->orderBy('id', 'DESC')
        ->first();
        // Return a view or other response with the submission details
        return view('client.submission.view', compact('submission','offer'));
    }
}
