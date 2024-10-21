<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunity;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesWorkOrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CareerOpportunitiesWorkorder::with('hiringManager','vendor','careerOpportunity');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('hiring_manager', function ($row) {
                    return $row->hiringManager->full_name ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function($row) {
                    return $row->careerOpportunity ? $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' : 'N/A';
                })
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('status', function($row) {
                    return CareerOpportunitiesWorkorder::getWorkorderStatus($row->status);
                })
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('duration', function ($row) {
                    return $row->workOrder && $row->workOrder->date_range
                        ? $row->workOrder->date_range
                        : 'N/A';
                })
                ->addColumn('submissions', function ($row) {
                    return $row->submissions_count;
                })
                ->addColumn('worker_type', function($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('admin.workorder.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Logic to get and display catalog items
        return view('admin.workorder.index'); // Assumes you have a corresponding Blade view
    }    //
    public function show($id)
    {
        $workorder = CareerOpportunitiesWorkorder::findOrFail($id);
        $rejectReasons =  Setting::where('category_id', 27)->get();        
        return view('admin.workorder.view', compact('workorder','rejectReasons'));
    }
public function withdrawWorkorder(Request $request)
{
    $workorder = CareerOpportunitiesWorkorder::findOrFail($request->workorder_id);
    $vendorSubmission = CareerOpportunitySubmission::findOrFail($workorder->submission_id);
    if ($vendorSubmission) {
        $vendorSubmission->rejected_type = 1;
        $vendorSubmission->rejected_by = Admin::getAdminIdByUserId(Auth::id());
        $vendorSubmission->resume_status = 12;
        $vendorSubmission->note_for_rejection = $request->note;
        $vendorSubmission->notes = $request->note;
        $vendorSubmission->reason_for_rejection = $request->reason;
        $vendorSubmission->date_rejected = now();        
        $vendorSubmission->Update(); // Save the changes
    }
    $offer = CareerOpportunitiesOffer::where('id', $workorder->offer_id)->first();                
    if ($offer) {
        $offer->status = 13;
        $offer->withdraw_reason = $request->reason;
        $offer->notes = $request->note;
        $offer->modified_by_id = Admin::getAdminIdByUserId(Auth::id());
        $offer->reason_rejection = $request->reason;
        $offer->date_modified = now();
        $offer->offer_rejection_date = now();
        $offer->modified_by_type = 1;
        $offer->Update();
    }
    $workorder = CareerOpportunitiesWorkorder::where('submission_id', $workorder->submission_id)->first();
    if ($workorder) {
        $workorder->status = 6;
        $workorder->rejection_date = now();
        $workorder->modified_by_id = Admin::getAdminIdByUserId(Auth::id());
        $workorder->modified_by_type = '1';
        $workorder->reason_rejection = $request->reason;
        $workorder->rejection_notes = $request->note;
        $workorder->Update();
    }
    session()->flash('success', 'Workorder/Submission Withdrawn successfully!');
    return response()->json([
        'success' => true,
        'message' => 'Workorder/Submission Withdrawn successfully!',
        'redirect_url' => route('admin.workorder.index'),
    ]);
}
}
