<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesContract;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesContractController extends Controller
{
    public function index(Request $request)
    {

        $counts = [
            'active' => CareerOpportunitiesContract::where('status', 1)->count(),
            'cancelled' => CareerOpportunitiesContract::whereIn('status', [2, 6])->count(),
            'additional_budget' => CareerOpportunitiesContract::whereHas('contractAdditionalBudgetRequest')->count(),
            'ext_req' => CareerOpportunitiesContract::whereHas('extensionRequest')->count(),
            'rate_change' => CareerOpportunitiesContract::whereHas('contractRateEditRequest')->count(),
        ];


        if ($request->ajax()) {
            $clientId = Client::getClientIdByUserId(Auth::id());
            $data = CareerOpportunitiesContract::with('hiringManager','careerOpportunity','workOrder.vendor','location');
            
            if ($request->has('type')) {
                $type = $request->input('type');
                switch ($type) {
                    case 'active':
                        $data->where('status', 1);
                        break;
                    case 'cancelled':
                        $data->whereIn('status', [2, 6]);
                        break;
                    case 'additional_budget':
                        $data->has('contractAdditionalBudgetRequest');
                        break;
                    case 'ext_req':
                        $data->has('extensionRequest');
                        break;
                    case 'rate_change':
                        $data->has('contractRateEditRequest');
                        break;
                        // Add additional cases as needed
                    default:
                        break; // Show all submissions if no type is specified
                }
            }
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return (isset($row->status)) ? $row->getContractStatus($row->status) : 'N/A';
                })
                ->addColumn('hiring_manager', function ($row) {
                    return (isset($row->hiringManager->full_name)) ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('consultant_name', function($row) {
                    return $row->consultant ? $row->consultant->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function ($row) {
                    return '<span class="job-detail-trigger text-blue-500 cursor-pointer" data-id="' . $row->careerOpportunity->id . '">' . $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' . '</span>';
                })
                ->addColumn('vendor_name', function ($row) {
                    return $row->workOrder && $row->workOrder->vendor
                        ? $row->workOrder->vendor->full_name
                        : 'N/A';
                })
                ->addColumn('duration', function ($row) {
                    return $row->date_range ? $row->date_range : 'N/A';
                })
                ->addColumn('worker_type', function($row) {
                    return $row->careerOpportunity && $row->careerOpportunity->workerType
                        ? $row->careerOpportunity->workerType->title
                        : 'N/A';
                })
                ->addColumn('location', function($row) {
                    return $row->location ? $row->location->name : 'N/A';
                })
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('vendor.contracts.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>';
                    return $btn;
                })
                ->rawColumns(['career_opportunity','action'])
                ->make(true);
        }
        return view('vendor.contract.index', compact('counts')); // Assumes you have a corresponding Blade view
    }
    public function show($id)
    {
        $contract = CareerOpportunitiesContract::with('careerOpportunity')->findOrFail($id);
        return view('vendor.contract.view', compact('contract'));
    }
}
