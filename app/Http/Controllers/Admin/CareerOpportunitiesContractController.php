<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\CareerOpportunity;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitiesContract;
use Yajra\DataTables\Facades\DataTables;

class CareerOpportunitiesContractController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $adminId = Admin::getAdminIdByUserId(Auth::id());
            $data = CareerOpportunitiesContract::with('hiringManager','careerOpportunity','workOrder.vendor');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return (isset($row->status)) ? $row->getContractStatus($row->status) : 'N/A';
                })
                ->addColumn('hiring_manager', function ($row) {
                    return (isset($row->hiringManager->full_name)) ? $row->hiringManager->full_name : 'N/A';
                })
                ->addColumn('career_opportunity', function($row) {
                    return $row->careerOpportunity ? $row->careerOpportunity->title . '('.$row->careerOpportunity->id.')' : 'N/A';
                })
                ->addColumn('vendor_name', function ($row) {
                    // Access vendor name via the workOrder relationship
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
                ->addColumn('action', function ($row) {
                    $btn = ' <a href="' . route('admin.contracts.show', $row->id) . '"
                       class="text-blue-500 hover:text-blue-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-eye"></i>
                     </a>
                     <a href="' . route('admin.contracts.edit', $row->id) . '"
                       class="text-green-500 hover:text-green-700 mr-2 bg-transparent hover:bg-transparent"
                     >
                       <i class="fas fa-edit"></i>
                     </a>';
                    $deleteBtn = '<form action="' . route('admin.contracts.destroy', $row->id) . '" method="POST" style="display: inline-block;" onsubmit="return confirm(\'Are you sure?\');">
                     ' . csrf_field() . method_field('DELETE') . '
                     <button type="submit" class="text-red-500 hover:text-red-700 bg-transparent hover:bg-transparent">
                         <i class="fas fa-trash"></i>
                     </button>
                   </form>';

                    return $btn . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        // Logic to get and display catalog items
        return view('admin.contract.index'); // Assumes you have a corresponding Blade view
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
        $rules = [

            'timesheetType' => 'required|integer',
            'candidateSourcing' => 'required|integer',
            'workorder_id' =>'required|integer',
         ];
         $messages = [

            // Add more custom messages as needed
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422); // 422 Unprocessable Entity status
        }
        $validatedData = $validator->validated();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
