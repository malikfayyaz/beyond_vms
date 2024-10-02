<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesOffer;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\CareerOpportunity;
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
                ->addColumn('vendor_name', function($row) {
                    return $row->vendor ? $row->vendor->full_name : 'N/A';
                })
                ->addColumn('duration', function ($row) {
                    return $row->date_range ? $row->date_range : 'N/A';
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
        return view('admin.workorder.view', compact('workorder'));
    }
}
