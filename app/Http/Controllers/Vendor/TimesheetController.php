<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\CareerOpportunitiesContract;
use App\Models\TimesheetProject;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class TimesheetController extends Controller
{
    //
    public function selectCandidate()
    {
       
       return view('vendor.timesheet.select_candidate');
    }

    public function stepOne(Request $request)
    {
        $rules = [
            'contract_id' => 'required|integer',
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
        // $contract = CareerOpportunitiesContract::findOrFail($request->contract_id);
        return response()->json([
            'success' => true,
            // 'message' => 'Offer saved successfully!',
            'redirect_url' => route('vendor.timesheet.step_one_view',  ['contract_id' => $request->contract_id]) // Redirect back URL for AJAX
        ]);
    }

    public function stepOneView($contract_id)
    {
       $contract =  CareerOpportunitiesContract::findOrFail($contract_id);
    //    dd($contract);
       return view('vendor.timesheet.step_one',[
        'contract'=>$contract
         ]);
    }

    public function stepOneStore(Request $request)
    {
        $rules = [
            'timesheet_duration' => 'required',
            'contract_id' => 'required|integer',
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
        $twoDates = explode('/',$validatedData['timesheet_duration']);
        // dd( $twoDates);
        // $contract = CareerOpportunitiesContract::findOrFail($request->contract_id);
        return response()->json([
            'success' => true,
            // 'message' => 'Offer saved successfully!',
            'redirect_url' => route('vendor.timesheet.timesheetStep2',  ['s' => $twoDates[0],'e'=>$twoDates[1],'contract_id' => $validatedData['contract_id']]) // Redirect back URL for AJAX
        ]);
    }

    public function step2(Request $request)
    {
        // dd($request);
        // Retrieve query parameters
        $startDate = $request->query('s');
        $endDate = $request->query('e');
        $contract_id = $request->query('contract_id');
        $contract =  CareerOpportunitiesContract::findOrFail($contract_id);
        $timesheetCostCenter = TimesheetProject::whereDate('effective_date', '<=', $endDate)
        ->where('status', 1)
        ->where('contract_id', $contract->id)
        ->get();
        // Your logic here
        return view('vendor.timesheet.step2', compact('timesheetCostCenter', 'contract', 'startDate', 'endDate'));
    }
}
