<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesInterview;
use App\Models\CareerOpportunitiesOffer;
use Yajra\DataTables\Facades\DataTables;

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
                return '<a href="' . route('vendor.interview.show', $row->id) . '"
                            class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent">
                                <i class="fas fa-eye"></i>
                        </a>';
            })
            ->make(true);
        }
        return view('vendor.interview.index');
    }

    public function show($id)
    {
        $interview = CareerOpportunitiesInterview::findOrFail($id);
        $offer = CareerOpportunitiesOffer::where('submission_id', $interview->submission_id)->first();
       
        return view('vendor.interview.view', compact('interview','offer'));
    }
}
