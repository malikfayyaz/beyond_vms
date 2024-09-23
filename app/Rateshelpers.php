<?php

if (!function_exists('calculateJobEstimates')) {
    function calculateJobEstimates($model) {
        
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

    if (!function_exists('vendorPayrate')) {
        function vendorPayrate($billRate, $markup)
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
    }
    
}