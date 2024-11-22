<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Facades\Timesheet as timesheet;
use App\Models\CareerOpportunitiesContract;
use App\Models\TimesheetProject;
use App\Models\CpTimesheet;
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

    public function stepTwoStore(Request $request)
    {
        // echo "<pre>"; print_r(json_decode($request->timesheet)); die();
        $validator = Validator::make($request->all(), [
            'projects' => 'required|array', // Ensure 'projects' is an array
            'projects.*' => 'integer', // Validate each element in the 'projects' array
            'startdate' => 'required|integer', // Validate as an integer (timestamp)
            'enddate' => 'required|integer|gt:startdate', // Ensure it's greater than 'startdate'
            'country_timesheet_sub_type' => 'required|integer', // Validate as an integer
            'timesheet' => 'required|json', // Ensure 'timesheet' is a valid JSON string
            'contract_id' => 'required|integer',
            'type' => 'required',
        ]);
        
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        
        // Additional custom validation for the 'timesheet' structure
        $timesheet = json_decode($request->input('timesheet'), true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($timesheet as $entry) {
                if (!isset($entry['day']) || !is_string($entry['day'])) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['timesheet' => ['Each entry in timesheet must have a valid "day" field.']],
                    ], 422);
                }
                if (!isset($entry['value']) || !is_numeric($entry['value'])) {
                    return response()->json([
                        'success' => false,
                        'errors' => ['timesheet' => ['Each entry in timesheet must have a valid "value" field.']],
                    ], 422);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'errors' => ['timesheet' => ['Invalid JSON format in the timesheet field.']],
            ], 422);
        }
        $contract = CareerOpportunitiesContract::findOrFail($request->contract_id);
        $workorder = $contract->workOrder;
        $candidate = $contract->consultant;
        $offer = $contract->offer;
        $invoiceStartDate = date('Y-m-d', $request->startdate);
        $invoiceEndDate = date('Y-m-d', $request->enddate);

        $invoiceStartDateDisplay = timesheet::setTimesheetDurationStart($invoiceStartDate);
        $invoiceEndDateDisplay = timesheet::setTimesheetDurationEnd($invoiceEndDate);
        $model = CpTimesheet::where('contract_id', $contract->id)
        ->where('invoice_start_date', $invoiceStartDate)
        ->where('invoice_end_date', $invoiceEndDate)
        ->whereIn('timesheet_status', [0, 1, 3])
        ->first();

        // If no model is found, create a new instance
        if (!$model) {
            $model = new CpTimesheet();
        }
        $timesheetCostCenter = TimesheetProject::whereDate('effective_date', '<=', $invoiceEndDate)
        ->where('status', 1)
        ->where('contract_id', $contract->id)
        ->get();
        $oldModelID = "";           
        $rejectedParent = 0;
        $approver = $workorder->approval_manager;
        $timesheetDuration = timesheet::setTimesheetDurationWeek($invoiceStartDate,$invoiceEndDate);
        if($contract->type_of_timesheet == 72 ) {
            timesheet::timesheetProCalc(0, $invoiceStartDate, $invoiceEndDate, $contract, $candidate, $contract->candidate_id, $workorder->id, $offer->id, $workorder->hiring_manager_id, $approver, $workorder->location_id, $timesheetDuration, '3', $oldModelID, $rejectedParent, $invoiceStartDateDisplay, $invoiceEndDateDisplay,$request);
        } else {
            timesheet::timesheetCalifCalcNew(0, $invoiceStartDate, $invoiceEndDate, $contract, $candidate, $contract->candidate_id, $workorder->id, $offer->id, $workorder->hiring_manager_id, $approver, $workorder->location_id, $timesheetDuration, '3', $oldModelID, $rejectedParent, $invoiceStartDateDisplay, $invoiceEndDateDisplay,$request);
        }

        $model = CpTimesheet::where('contract_id', $contractID)
            ->where('invoice_start_date', $invoiceStartDate)
            ->where('invoice_end_date', $invoiceEndDate)
            ->orderBy('id', 'desc')
            ->first();
        timesheet::calculateTaxesOnSubmissionOfTimesheet($model, '3');
        return response()->json([
            'success' => true,
            // 'message' => 'Offer saved successfully!',
            'redirect_url' => route('vendor.timesheet.timesheetStep2',  ['s' => $twoDates[0],'e'=>$twoDates[1],'contract_id' => $validatedData['contract_id']]) // Redirect back URL for AJAX
        ]);
    }
}
