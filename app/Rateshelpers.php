<?php

if (!function_exists('calculateJobEstimates')) {
    function calculateJobEstimates($model) {
        // dd($model); 
        $noOfOpenings = $model->num_openings;
        $startDate = date('Y-m-d',strtotime($model->start_date));
        $endDate = date('Y-m-d',strtotime($model->end_date));
        $hoursPerDay = $model->hours_per_day;
        $daysPerWeek = $model->day_per_week;

        $noOfDays = numberOfWorkingDays($startDate,$endDate);
        $extraDays = $noOfDays%5;
        $numberOfWeeks = ($noOfDays - $extraDays) / 5;

        $totalNumberOfHours = $numberOfWeeks*$daysPerWeek*$hoursPerDay + $extraDays*$hoursPerDay;

        $billRate = $model->bill_rate;
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

        return true;
    }
}