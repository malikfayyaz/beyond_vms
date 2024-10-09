<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CareerOpportunitiesContract;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\Admin;

class CareerOpportunitiesContractController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $workOrderModel = CareerOpportunitiesWorkorder::model()->findByPk($request->workorder_id);
        $contractModel = new CareerOpportunitiesContract;
                $contractModel->workorder_id = $workOrderModel->id;
                $contractModel->offer_id = $workOrderModel->offer_id;
                $contractModel->created_by_id = Admin::getAdminIdByUserId(Auth::id());
                $contractModel->submission_id = $workOrderModel->submission_id;
                $contractModel->career_opportunity_id = $workOrderModel->career_opportunity_id;
                $contractModel->hiring_manager_id = $workOrderModel->hiring_manager_id;
                $contractModel->vendor_id = $workOrderModel->vendor_id;
                $contractModel->candidate_id = $workOrderModel->candidate_id;
                $contractModel->status = 1;
                $contractModel->start_date = Carbon::createFromFormat('m/d/Y', $request->startDate)->format('Y-m-d');
                $contractModel->end_date = Carbon::createFromFormat('m/d/Y', $request->endDate)->format('Y-m-d');
                // $contractModel->start_date = $request->;
                // $contractModel->end_date = $request->;
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
