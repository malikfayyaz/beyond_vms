<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\CareerOpportunitiesContract;
use App\Models\ContractRate;
use App\Models\Workorder;

class RateshelpersService
{
    
        public static function calculateJobEstimates($model) {
            
            $noOfOpenings = $model->num_openings;
            $startDate = date('Y-m-d',strtotime($model->start_date));
            $endDate = date('Y-m-d',strtotime($model->end_date));
            $hoursPerDay = $model->hours_per_day;
            $daysPerWeek = $model->day_per_week;
    
            $noOfDays = numberOfWorkingDays($startDate,$endDate);
            $extraDays = $noOfDays%5;
            $numberOfWeeks = ($noOfDays - $extraDays) / 5;
            
            $totalNumberOfHours = $numberOfWeeks*$daysPerWeek*$hoursPerDay + $extraDays*$hoursPerDay;
           
            $billRate = $model->min_bill_rate;
            $overtimeCost = $model->overtime_hours_cost;
            $otherExpensesCost = $model->expense_cost;
    
            if($model->payment_type == '35'){
                $regularHoursBudget = $numberOfWeeks*$billRate;
                $singleResourceBudget = $regularHoursBudget + $overtimeCost + $otherExpensesCost;
                $allResourceBudget = $singleResourceBudget * $noOfOpenings;
            }elseif($model->payment_type == '34'){
                $regularHoursBudget = $noOfDays*$billRate;
                $singleResourceBudget = $regularHoursBudget + $overtimeCost + $otherExpensesCost;
                $allResourceBudget = $singleResourceBudget * $noOfOpenings;
            }else{
                $regularHoursBudget = $totalNumberOfHours*$billRate;
                $singleResourceBudget = $regularHoursBudget + $overtimeCost + $otherExpensesCost;
                $allResourceBudget = $singleResourceBudget * $noOfOpenings;
            }
    
    
    
            $model->regular_hours_cost = $regularHoursBudget;
            $model->single_resource_total_cost = $singleResourceBudget;
            $model->all_resources_total_cost = $allResourceBudget;
            $model->pre_total_estimate_code = $allResourceBudget;
            $model->save();
            // dd($model); 
            return true;
        }
    
       
            public static function vendorPayrate($billRate, $markup)
            {
                $markup = abs($markup);
                // Check for division by zero or invalid markup
                if (empty($markup) || $markup == 0) {
                    return null;  // Return null for better handling in Laravel
                }
        
                // Calculate pay rate
                $payRate = $billRate * (100 / (100 + $markup));
        
                // Format pay rate to two decimal places
                return number_format((float) $payRate, 2, '.', '');
            }
        
    
       
         //vendor rates calculation for offer, workorder, contracts
            public static function calculateVendorRates($model,$billRate,$overtimeBillrate,$doubletimeBillrate,$mspPercentage =0){
                //echo '<pre>';print_r($model);exit;
                /*Vendor Rates Calculations*/
                $vendorBillRate = $billRate - ($billRate*(0/100));
                $vendorOvertimeRate = $overtimeBillrate - ($overtimeBillrate*(0/100));
                $vendorDoubleRate = $doubletimeBillrate - ($doubletimeBillrate*(0/100));
    
                $model->vendor_bill_rate = $vendorBillRate;
                $model->vendor_overtime = $vendorOvertimeRate;
                $model->vendor_doubletime = $vendorDoubleRate;
    
                $model->save();
                return true;
                /*Vendor Rates Calculations*/
            }
        
    
            //vendor rates calculation for offer, workorder, contracts
            public static function calculateOfferEstimates($model,$jobData){
                $startDate = date('Y-m-d',strtotime($model->start_date));
                $endDate = date('Y-m-d',strtotime($model->end_date));
                $noOfDays =numberOfWorkingDays($startDate,$endDate);
    
                $estimatedBudget = self::estimateWithPaymentType($noOfDays,$model->offer_bill_rate,$jobData);
                $model->estimate_cost = $estimatedBudget;
                $model->save();
    
                return true;
                /*Vendor Rates Calculations*/
            }
        
    
    
            public static function estimateWithPaymentType($noOfDays,$billRate,$jobData){
    
                $hoursPerDay = $jobData->hours_per_day;
                $daysPerWeek = $jobData->day_per_week;
    
                $extraDays = $noOfDays%5;
                $numberOfWeeks = ($noOfDays - $extraDays) / 5;
                $totalNumberOfHours = $numberOfWeeks*$daysPerWeek*$hoursPerDay + $extraDays*$hoursPerDay;
    
                if($jobData->payment_type == '35'){
                    $estimates = $numberOfWeeks*$billRate;
                }elseif($jobData->payment_type == '34'){
                    $estimates = $noOfDays*$billRate;
                }else{
                    $estimates = $billRate * $totalNumberOfHours;
                }
    
                return $estimates;
            }
        
    
        //workorder rates
            public static function calculateWorkorderEstimates($model,$jobData){
                $startDate = date('Y-m-d',strtotime($model->start_date));
                $endDate = date('Y-m-d',strtotime($model->end_date));
                $noOfDays = numberOfWorkingDays($startDate,$endDate);
    
                $estimatedBudget = self::estimateWithPaymentType($noOfDays,$model->wo_bill_rate,$jobData);
                $model->single_resource_job_approved_budget = $estimatedBudget;
                $model->job_other_amount = $jobData->overtime_hours_cost+$jobData->expense_cost;
                $model->save();
            }
        
    
         //Assignment Rates
            public static function calculateContractEstimates($contract,$workorder,$jobData){
                $startDate = date('Y-m-d',strtotime($contract->start_date));
                $endDate = date('Y-m-d',strtotime($contract->end_date));
                $noOfDays = numberOfWorkingDays($startDate,$endDate);
    
                $estimatedBudget = self::estimateWithPaymentType($noOfDays,$workorder->wo_bill_rate,$jobData);
                $contract->total_estimated_cost = $estimatedBudget;
                $contract->job_other_amount = $workorder->job_other_amount;
                $contract->save();
            }

            public static function returnContractEffectiveRate($id)
            {
                $contract = CareerOpportunitiesContract::findOrFail($id);
                $endDate = now()->format('Y-m-d');
                $contractID = $contract->id;
                // Define query to get contract rate ID based on start date and effective date
                if ($contract->start_date > $endDate) {
                    $contractRateID = DB::table('contract_rates')
                        ->where('contract_id', $contractID)
                        ->selectRaw('MAX(id) as id, MAX(effective_date) as effective_date')
                        ->groupBy('effective_date')
                        ->orderByDesc('effective_date')
                        ->value('id'); // Get the MAX id
                } else {
                    $contractRateID = DB::table('contract_rates')
                        ->where('effective_date', '<=', $endDate)
                        ->where('contract_id', $contractID)
                        ->selectRaw('MAX(id) as id, MAX(effective_date) as effective_date')
                        ->groupBy('effective_date')
                        ->orderByDesc('effective_date')
                        ->value('id'); // Get the MAX id
                }
                

                // Fetch the contract rate by ID
                $contractRate = ContractRate::where('id', $contractRateID)->orderBy('id', 'desc')->first();

                // Return the module data with rates
                $moduleData = [
                    'pay_rate' => $contractRate->candidate_pay_rate,
                    'contractor_overtime_pay_rate' => $contractRate->candidate_overtime_rate,
                    'contractor_double_time_rate' => $contractRate->candidate_doubletime_rate,
                    'vendor_bill_rate' => $contractRate->vendor_bill_rate,
                    'vendor_overtime_bill_rate' => $contractRate->vendor_overtime_rate,
                    'vendor_doubletime_bill_rate' => $contractRate->vendor_doubletime_rate,
                    'bill_rate' => $contractRate->client_bill_rate,
                    'client_overtime_bill_rate' => $contractRate->client_overtime_rate,
                    'client_doubletime_bill_rate' => $contractRate->client_doubletime_rate,
                ];

                return $moduleData;
            }

    public static function number_of_working_days($startDate,$endDate) {
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);
        $days = ($endDate - $startDate) / 86400 + 1;
        $no_full_weeks = floor($days / 7);
        $no_remaining_days = fmod($days, 7);
        $the_first_day_of_week = date("N", $startDate);
        $the_last_day_of_week = date("N", $endDate);
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
        }
        else {
            if ($the_first_day_of_week == 7) {
                $no_remaining_days--;
                if ($the_last_day_of_week == 6) {
                    $no_remaining_days--;
                }
            }
            else {
                $no_remaining_days -= 2;
            }
        }
       $workingDays = $no_full_weeks * 5;
        if ($no_remaining_days > 0 )
        {
          $workingDays += $no_remaining_days;
        }
        return round($workingDays);
    }

    public static function checkSowStatus($workorder_id) {
            return true;
    }

    public static function estimateWithPaymentType($noOfDays,$billRate,$jobData){

        $hoursPerDay = $jobData->hours_per_day;
        $daysPerWeek = $jobData->day_per_week;

        $extraDays = $noOfDays%5;
        $numberOfWeeks = ($noOfDays - $extraDays) / 5;
        $totalNumberOfHours = $numberOfWeeks*$daysPerWeek*$hoursPerDay + $extraDays*$hoursPerDay;

        if($jobData->payment_type == 'Per Week'){
            $estimates = $numberOfWeeks*$billRate;
        }elseif($jobData->payment_type == 'Per Day'){
            $estimates = $noOfDays*$billRate;
        }else{
            $estimates = $billRate * $totalNumberOfHours;
        }

        return $estimates;
    }

    
}

