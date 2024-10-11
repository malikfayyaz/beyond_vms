<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Consultant;
use App\Models\CareerOpportunity;
use App\Models\CareerOpportunitiesContract;
use App\Models\CareerOpportunitiesWorkorder;
use Carbon\Carbon;

class VendorController extends BaseController
{
    public function index()
    {
        return view('vendor.dashboard');
    }

    public function consultantDetail(Request $request)
    {
        // Validate that the candidate_id is present
        $request->validate([
            'candidate_id' => 'required|exists:consultants,id',
            'job_id' => 'required|exists:career_opportunities,id',
        ]);
        $vendorID =  checkUserId(\Auth::id(),session('selected_role'));
        $vendorID = superVendor($vendorID);
        $jobModel = CareerOpportunity::findOrFail($request->job_id);
        $jobStartDate = date("Y-m-d",strtotime($jobModel->start_date));
        $contractModel = CareerOpportunitiesContract::where('candidate_id', $request->candidate_id)
            ->orderBy('id', 'desc')
            ->first();

        $workorderModel = CareerOpportunitiesWorkorder::where('candidate_id', $request->candidate_id)
            ->where('on_board_status', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();
            $error = "";
        if($contractModel !=null){
        $contractEndDate =getMiscContractEndDate($contractModel->id);
        $contractEndDate = date("Y-m-d",strtotime($contractEndDate));
        
        if($jobStartDate<$contractEndDate){
            $error = 'This candidate already exists in the system and have active assignment';
            
        }}else if($workorderModel !=null){
            $error = 'This candidate already exists in the system and have active offer';
            

        }
        // Fetch the consultant's details using the candidate ID
        $consultant = Consultant::findOrFail($request->input('candidate_id'));

        if (!$consultant) {
            return response()->json(['message' => 'Consultant not found'], 404);
        }

        // Return a JSON response with the consultant's details
        return response()->json([
            'candidateFirstName' => $consultant->first_name,
            'candidateMiddleName' => $consultant->middle_name,
            'candidateLastName' => $consultant->last_name,
            'dobDate' => Carbon::parse($consultant->dob)->format('d/m/Y'),
            'lastFourNationalId' => substr($consultant->national_id, -4),
            'candidateEmial' => $consultant->user->email,
            'error' => $error,
            // Add more fields as needed
        ]);
    }
    //
    // vendor markup
    public function showVendorMarkup(Request $request)
    {
        // dd("sfdsf");
        $vendorID = Vendor::getVendorIdByUserId(\Auth::id());
        $jobID = $request->input('jobid');
        $adjustedMarkup = $request->input('adjustedMarkup', 0);
        $markup = $request->input('markup', 0);

        $candidatePayRate = $request->input('payRate', 0);
        $vendorBillRate = $request->input('billRate', 0);
        $rateField = $request->input('rateField', '');

        // Find the job using the provided ID
        $jobModel = CareerOpportunity::findOrFail($jobID);
        // dd($jobModel);
        // Recommended pay rate calculation
        $recommendedPayRate = vendorPayrate($jobModel->min_bill_rate, $adjustedMarkup);

        // Initialize data array
        $data = [
            'recommended_pay_rate' => $recommendedPayRate,
            'candidate_pay_rate' => $candidatePayRate,
            'candidate_bill_rate' => number_format($vendorBillRate, 2, '.', '')
        ];

        // Main calculation based on rate field change
        switch ($rateField) {
            case 'bill_rate_changed':
                // $data['candidate_bill_rate'] = number_format($vendorBillRate, 2, '.', '');
                $data['candidate_pay_rate'] = $candidatePayRate;
                break;

            case 'adjusted_markup_changed':
                $data['candidate_bill_rate'] = number_format($candidatePayRate + ($candidatePayRate * $adjustedMarkup / 100), 2, '.', '');
                $data['candidate_pay_rate'] = vendorPayrate($data['candidate_bill_rate'], $adjustedMarkup);
                break;
        }


        // Adjusted markup calculation if candidate pay rate exists
        if (!empty($data['candidate_pay_rate'])) {
            $data['adjusted_markup'] = round((($data['candidate_bill_rate'] - $data['candidate_pay_rate']) / $data['candidate_pay_rate']) * 100, 2);
        }

        $payrate = getActiveRecordsByType('pay-rate')->first();
        $billrate = getActiveRecordsByType('bill-rate')->first();


        $data['over_time']  = number_format($data['candidate_pay_rate']+($payrate->name*$data['candidate_pay_rate']),2,'.', '');

        $data['client_over_time'] = number_format($data['candidate_bill_rate']+($data['candidate_bill_rate']*$billrate->name),2,'.', '');

        $data['double_over_time']  = number_format($data['candidate_pay_rate']+($payrate->value*$data['candidate_pay_rate']),2,'.', '');

        $data['client_double_over_time']  =  number_format($data['candidate_bill_rate']+($data['candidate_bill_rate']*$billrate->value),2,'.', '');
        $clientBillRate   = $data['candidate_bill_rate'];

        $vendorBillRateNew = $clientBillRate- ($clientBillRate*(0/100));

        $data['vendor_bill_rate']  = number_format($vendorBillRateNew,2,'.', '');

        // Return JSON response
        return response()->json([
            'over_time' => $data['over_time'],
            'double_time_rate' => $data['double_over_time'],
            'client_over_time_rate' => $data['client_over_time'],
            'client_double_time_rate'=> $data['client_double_over_time'],
            'vendor_bill_rate' => $data['vendor_bill_rate'],
            'adjustedMarkup' => abs($data['adjusted_markup']),
            'recommendedPayRate' => $data['recommended_pay_rate'],
            // Add more fields as needed
        ]);
    }

}
