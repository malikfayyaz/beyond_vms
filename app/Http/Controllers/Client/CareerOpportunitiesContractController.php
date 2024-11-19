<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\CareerOpportunitiesContract;
use App\Models\Client;
use App\Models\ContractNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesContractController extends Controller
{
    public function index(Request $request)
    {
        $counts = [
            'active' => CareerOpportunitiesContract::where('status', 1)->count(),
            'cancelled' => CareerOpportunitiesContract::where('status', 2)->count(),
            'additional_budget' => CareerOpportunitiesContract::whereHas('contractAdditionalBudgetRequest')->count(),
            'ext_req' => CareerOpportunitiesContract::whereHas('extensionRequest')->count(),
            'rate_change' => CareerOpportunitiesContract::whereHas('contractRateEditRequest')->count(),
            'pending_AB' => CareerOpportunitiesContract::whereHas('pendingBudgetRequests')->count(),
            'pending_ER' => CareerOpportunitiesContract::whereHas('pendingExtensionRequests')->count(),
            'pending_RC' => CareerOpportunitiesContract::whereHas('pendingRateChangeRequests')->count(),
        ];
// dd($counts);
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
                        $data->where('status', 2);
                        break;
                    case 'pending_AB':
                        $data->has('pendingBudgetRequests');
                        break;
                    case 'pending_ER':
                        $data->has('pendingExtensionRequests');
                        break;
                    case 'pending_RC':
                        $data->has('pendingRateChangeRequests');
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
                    $btn = ' <a href="' . route('client.contracts.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('client.contracts.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('client.contracts.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                     ' . csrf_field() . method_field('DELETE') . '
                     <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                         <i class="fas fa-trash"></i>
                     </button>
                   </form>';

                    return $btn . $deleteBtn;
                })
                ->rawColumns(['career_opportunity','action'])
                ->make(true);
        }
        return view('client.contract.index', compact('counts')); // Assumes you have a corresponding Blade view
    }
    public function show($id)
    {
        $contract = CareerOpportunitiesContract::with('careerOpportunity')->findOrFail($id);
        return view('client.contract.view', compact('contract'));
    }
    public function saveComments(Request $request) //SAVENOTES
    {
        //   dd($request->all());
        $request->validate([
            'note' => 'required|string',
            'contract_id' => 'required|integer'
        ]);
        $note = new ContractNote();
        $note->contract_id = $request->contract_id;
        $note->user_id = Auth::id();
        $note->notes = $request->note;
        $note->posted_by_type = Auth::user()->role == 'Client' ? 'Client' : 'Admin';
        $note->save();
        session()->flash('success', 'Notes Added Successfully');
        return response()->json([
            'success' => true,
            'message' => 'Notes Added Successfully',
            'posted_by' => Auth::user()->name,
            'created_at' => $note->created_at->format('m/d/Y H:i A'),
            'redirect_url' => route('client.contracts.show', $note->contract_id) // Redirect back URL for AJAX
        ]);
    }
}
