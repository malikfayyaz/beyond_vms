<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RatesController extends BaseController
{
    public function jobRates(Request $request)
    {
        $data = [];



        // Sanitize input
        $billRate = removeComma($request->input('bill_rate'));
        $otherAmountSum = removeComma($request->input('other_amount_sum'));
        if($request->input('start_date')){
            $startDay = Carbon::createFromFormat('m/d/Y', $request->input('start_date'))->format('Y-m-d');
        }
        else
        {
            $startDay = '';
        }
        if($request->input('end_date')){
            $endDay = Carbon::createFromFormat('m/d/Y', $request->input('end_date'))->format('Y-m-d');
        }
        else{
            $endDay = '';
        }
        $hoursPerDay = $request->input('hours_per_day');
        $daysPerWeek = $request->input('days_per_week');
        $opening = $request->input('opening');

        // Calculate the number of working days
        $noOfDays = numberOfWorkingDays($startDay, $endDay);
        $extraDays = $noOfDays % 5;
        $numberOfWeeks = ($noOfDays - $extraDays) / 5;

        // Calculate total hours
        $totalNumberOfHours = ($numberOfWeeks * $daysPerWeek * $hoursPerDay) + ($extraDays * $hoursPerDay);

        // Calculate costs
        $singleResourceCost = ($billRate * $totalNumberOfHours) + $otherAmountSum;
        $regularBillRate = ($billRate * $totalNumberOfHours);
        $allResourceCost = $singleResourceCost * $opening;

        // Prepare the response data
        $data['singleResourceCost'] = number_format($singleResourceCost, 2);
        $data['allResourceCost'] = number_format($allResourceCost, 2);
        $data['regularBillRate'] = number_format($regularBillRate, 2);
        $data['regularBillRateAll'] = number_format($regularBillRate * $opening, 2);
        $data['totalHours'] = $totalNumberOfHours;
        $data['numOfWeeks'] = "{$numberOfWeeks} Weeks {$extraDays} Days";

        // Return the response as JSON
        return response()->json($data);
    }


}
