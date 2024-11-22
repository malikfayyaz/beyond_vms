<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\TimesheetProject;
use App\Models\ContractRate;
use App\Models\CpTimesheet;
use App\Models\CpTimesheetDetail;
class TimesheetService
{

    public static function setTimesheetDurationWeek($startDate,$endDate){
        if(date('D',strtotime($startDate)) != 'Sun'){
            $startDate = date('m-d-Y',strtotime('last Sunday', strtotime($startDate)));
        }else{
            $startDate = date('m-d-Y',strtotime($startDate));
        }

        if(date('D',strtotime($endDate)) != 'Sat'){
            $endDate = date('m-d-Y',strtotime('next Saturday', strtotime($endDate)));
        }else{
            $endDate = date('m-d-Y',strtotime($endDate));
        }


        $invoiceDuration =$startDate.'/'.$endDate;

        return $invoiceDuration;
    }

    public static function setTimesheetDurationStart($startDate){
        if(date('D',strtotime($startDate)) != 'Sun'){
            $startDate = date('Y-m-d',strtotime('last Sunday', strtotime($startDate)));
        }else{
            $startDate = date('Y-m-d',strtotime($startDate));
        }

        $invoiceDuration = $startDate;

        return $invoiceDuration;
    }

    public static function setTimesheetDurationEnd($endDate){
        if(date('D',strtotime($endDate)) != 'Sat'){
            $endDate = date('Y-m-d',strtotime('next Saturday', strtotime($endDate)));
        }else{
            $endDate = date('Y-m-d',strtotime($endDate));
        }

        $invoiceDuration = $endDate;

        return $invoiceDuration;
    }

    public static function timesheetProCalc($timesheetID,$invoiceStartDate,$invoiceEndDate,$contract,$candidate,$loginUser,$workorderID,$offerID,$clientID,$approver,$locationID,$timesheetDuration,$portal,$oldModelID="",$rejectedParent="",$invoiceStartDateDisplay="",$invoiceEndDateDisplay="",$request){

        //  echo '<pre>';print_r($request->all());exit;
  
          $allowedRegHours = 40;
          $perDayCheck = false;
          $perDayAllowedHours = 0;
          $perDayAllowedRemainingHours = 0;
          $timesheetData = json_decode($request->input('timesheet'), true);
          foreach ($request->input('projects') as $projectKey => $projectId) {
           // Find the corresponding timesheet project
           $timesheetProject = TimesheetProject::where('contract_id', $request->input('contract_id'))
            ->where('bu_id', $projectId)
            ->first();
        
            if (!$timesheetProject) {
                continue; // Skip if no timesheet project is found
            }
        
            // Iterate through each day in the timesheet data
            foreach ($timesheetData as $dayKey => $dayData) {
                $day = $dayData['day'];
                $hours = (float) $dayData['value'];
        
                if ($hours > 0) {
                    // Map the day to the timesheet project's ID
                    $result['days'][$day][$timesheetProject->id] = $hours;
        
                    // Track the total hours for each day
                    if (!isset($result['total_hours_per_day'][$day])) {
                        $result['total_hours_per_day'][$day] = 0;
                    }
                    $result['total_hours_per_day'][$day] += $hours;
        
                    // Track the daily hours
                    $result['daily_hours_val'][$day] = $hours;
                }
            }
        }
       
  
          if(isset($result['day'])){
              $result['days'] = $result['day'];
          }
  
          echo '<pre>';print_r($result['days']);exit;
  
          $submission = $contract->submission;
          $jobData = $contract->careerOpportunity;
  
          //echo strtolower($jobData->jobLocation->code);exit;
  
         //echo '<pre>'; print_r($_POST['days']);exit;
  
          $parentTimesheetID = 0;$parentInvoiceID = 0;
          if(isset($request->rate_selection)) {
              $oldModel = CpTimesheet::findOrFail($oldModelID);
              $oldInvoice  = GeneratedInvoice::where('timsheet_id', $oldModel->id)->first();

              $parentTimesheetID = $oldModel->id;
              $parentInvoiceID = $oldInvoice->id;
          }
  
          if(isset($request->rate_selection) && $request->rate_selection != 2){
              //bill rate and pay rate all values
              $regular_payrate = $oldModel->regular_payrate;
              $overtime_payrate = $oldModel->overtime_payrate;
              $doubletime_payrate = $oldModel->doubletime_payrate;
  
              $regular_billrate = $oldModel->regular_billrate;
              $overtime_billrate = $oldModel->overtime_billrate;
              $doubletime_billrate = $oldModel->doubletime_billrate;
  
              $marking_billrate = $oldModel->marking_billrate;
              $marking_vendor_billrate = $oldModel->marking_vendor_billrate;
              $marking_payrate = $oldModel->marking_payrate;
          }else{
              //contract rate update
              //$contractRate = ContractRate::model()->find(array('condition'=>'DATE_FORMAT(t.effective_date, "%Y-%m-%d") <= DATE_FORMAT("'.$invoiceEndDate.'", "%Y-%m-%d") AND t.costcenter_config_id = 0 AND t.contract_id = '.$contract->id.' ORDER BY t.effective_date DESC, t.id DESC'));
              $contractRate =  self::findContractRate($invoiceEndDate,$contract->id);
  
              //candidate payrate data
              $regular_payrate = $contractRate->candidate_pay_rate;
              $overtime_payrate = $contractRate->candidate_overtime_rate;
              $doubletime_payrate = $contractRate->candidate_doubletime_rate;
  
              //client billrate data
              $regular_billrate = $contractRate->client_bill_rate;
              $overtime_billrate = $contractRate->client_overtime_rate;
              $doubletime_billrate = $contractRate->client_doubletime_rate;
  
              $marking_billrate = $contractRate->client_marking_rate;
              $marking_vendor_billrate = $contractRate->vendor_marking_rate;
              $marking_payrate = $contractRate->candidate_marking_rate;
  
              //If candidate ovetime is exempted than regular rates will be implemented.
              if($submission->ot_exempt_position=='Yes'){
                  $overtime_payrate = $contractRate->candidate_pay_rate;
                  $overtime_billrate = $contractRate->client_bill_rate;
  
                  $doubletime_payrate = $contractRate->candidate_pay_rate;
                  $doubletime_billrate = $contractRate->client_bill_rate;
              }
          }
  
          if($timesheetID != 0){
              $modelOldData = CpTimesheet::findOrFail($timesheetID);
            //   TimesheetCostcenterTempTable::model()->deleteAllByAttributes(array('timesheet_id'=>$modelOldData->id));
                CpTimesheetDetail::where('timesheet_id', $modelOldData->id)->delete();
              $modelOldData->total_regular_hours = 0;
              $modelOldData->total_overtime_hours = 0;
              $modelOldData->total_doubletime_hours = 0;
              $modelOldData->total_hours = 0;
              $modelOldData->total_regular_payrate = 0;
              $modelOldData->total_overtime_payrate = 0;
              $modelOldData->total_doubletime_payrate = 0;
              $modelOldData->total_payrate = 0;
              $modelOldData->total_regular_billrate = 0;
              $modelOldData->total_overtime_billrate = 0;
              $modelOldData->total_doubletime_billrate = 0;
              $modelOldData->total_billrate = 0;
              $modelOldData->save(false);
          }
          $i = 1;
          $model  = CpTimesheet::where('contract_id', $contract->id)
          ->where('invoice_start_date', $invoiceStartDate)
          ->where('invoice_end_date', $invoiceEndDate)
          ->whereIn('timesheet_status', [0, 1, 3])
          ->where('unmatched_reason', '')
          ->orderBy('id', 'desc')
          ->first();
          foreach( $result['days'] as $key1=>$value1 ){
  
              if (!self::dateIsInBetween($contract->start_date, $contract->end_date, $key1)) continue;
  
              $timecard_sub_config = UtilityManager::timeCardSubConfiguration();
              $timesheetSubTypeId =  $model->sub_type_of_timesheet;
              if(  strtolower($jobData->payment_type) == strtolower('per hour')){
                  $allowedRegHours = 40;
              }else{
                  $allowedRegHours = 168;
              }
  
              
  
              $todayExistingRHours = 0;
              $leaveReason = 0;
  
              if(isset($request->leave_reason)){
                  $leaveReason = $request['leave_reason'][$key1][0];
              }
  
              //if hours value exist then insert record otherwise not. //$key1 is the Date;
              foreach($value1 as $key=>$value){
                  $project_code_id = $key;
                  //if its new record then this code will run
                  if (empty($model) || ($parentTimesheetID!=0 && $i==1)) {
                      $model = new CpTimesheet;
                      $model->parent_id = $parentTimesheetID;
                      $model->rejected_child = $rejectedParent;
                      $model->parent_invoice_id = $parentInvoiceID;
                      $model->contract_id = $contract->id;
                     
                      $model->candidate_id = $loginUser;
                      $model->client_id = $clientID;
                      $model->created_from = $portal;
                      $model->submitted_from = $portal;
  
                      //saving all type of billrate and payrates in main table.
                      $model->regular_payrate = $regular_payrate;
                      $model->overtime_payrate = $overtime_payrate;
                      $model->doubletime_payrate = $doubletime_payrate;
                      $model->regular_billrate = $regular_billrate;
                      $model->overtime_billrate = $overtime_billrate;
                      $model->doubletime_billrate = $doubletime_billrate;
  
                      //marking rates.
                      $model->marking_billrate = $marking_billrate;
                      $model->marking_vendor_billrate = $marking_vendor_billrate;
                      $model->marking_payrate = $marking_payrate;
  
                      $model->approval_manager = $approver;
                      $model->location_id = $locationID;
                      $model->client_config_type = 2;
                      $model->invoice_start_date = $invoiceStartDate;
                      $model->invoice_end_date = $invoiceEndDate;
                      $model->invoice_start_date_display = $invoiceStartDateDisplay;
                      $model->invoice_end_date_display = $invoiceEndDateDisplay;
                      $model->format_option = 4;
                      $model->invoice_duration = $timesheetDuration;
                      $model->timesheet_status = 3;
                     
                      $model->type_of_timesheet = 'No-Project';
                      $model->sub_type_of_timesheet = $contract->sub_type_of_timesheet;
                      $sub_hour_type = 'Regular Hour';
  
                      //total number of hours
                      if($perDayCheck == true && $value > $perDayAllowedHours){
                          $model->total_hours = $value;
                          $model->total_regular_hours = $perDayAllowedRemainingHours;
                          $model->total_overtime_hours = $value - $perDayAllowedRemainingHours;
                      }else{
                          $model->total_hours = $value;
                          $model->total_regular_hours = $value;
                      }
  
                      if($parentTimesheetID > 0){
                          //if its modify timesheet
                          //$model->created_from = $oldModel->created_from;
                          $model->type_of_timesheet = $oldModel->type_of_timesheet;
                          /*$model->timesheet_sub_status = $oldModel->timesheet_sub_status;
                          $model->timesheet_status = 0;*/
                          //$model->submitted_from = $oldModel->submitted_from;
                          $model->accept_rejected_by = $oldModel->accept_rejected_by;
                          $model->rejection_date_time = $oldModel->rejection_date_time;
                          $model->approve_date_time = $oldModel->approve_date_time;
                          $model->parent_invoice_id = $oldInvoice->id;
                          $model->date_submitted = date('Y-m-d H:i:s');
                          $model->cd_memo_type = $_POST['cd_memo_type'];
                          $model->cd_memo_reason = $_POST['cd_memo_reason'];
                          $model->cd_memo_notes = $_POST['cd_memo_notes'];
                          $model->rate_type_for_modified = $_POST['rate_selection'];
                      }
  
                      $model->save(false);
  
                      if($perDayCheck == true && $value > $perDayAllowedHours){
                          $noOfHours = $model->total_regular_hours;
                          $postedValue = array(
                              'creation_day' => $key1,
                              'project' => $project_code_id,
                              'notes' => $_POST['notes'][$key1][$key],
                              'leave_reason' => $leaveReason
                          );
                          self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
  
                          $i++;
  
                          $sub_hour_type = 'Over Time';
                          $noOfHours = $model->total_overtime_hours;
                          $postedValue = array(
                              'creation_day' => $key1,
                              'project' => $project_code_id,
                              'notes' => $_POST['notes'][$key1][$key],
                              'leave_reason' => $leaveReason
                          );
                          self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
  
                          $perDayAllowedRemainingHours = 0;
                      }else{
                          $noOfHours = $value;
                          $postedValue = array(
                              'creation_day' => $key1,
                              'project' => $project_code_id,
                              'notes' => $_POST['notes'][$key1][$key],
                              'leave_reason' => $leaveReason
                          );
                          self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
  
                          $perDayAllowedRemainingHours = $perDayAllowedRemainingHours - $value;
                      }
  
                      $i++;
                  } else {
  
                      $model->regular_payrate = $regular_payrate;
                      $model->overtime_payrate = $overtime_payrate;
                      $model->doubletime_payrate = $doubletime_payrate;
                      $model->regular_billrate = $regular_billrate;
                      $model->overtime_billrate = $overtime_billrate;
                      $model->doubletime_billrate = $doubletime_billrate;
  
                      //marking rates.
                      $model->marking_billrate = $marking_billrate;
                      $model->marking_vendor_billrate = $marking_vendor_billrate;
                      $model->marking_payrate = $marking_payrate;
  
                      $model->approval_manager = $approver;
                      $model->location_id = $locationID;
                      $model->date_updated = date('Y-m-d H:i:s');
                      $model->save();
  
                      //if already record exist in main table then this portion will run.
                      $hours = $model->total_regular_hours + $value;
  
                      //current day date.
                      $todayDate = $key1;
                      $timesheet_detail_q = "SELECT SUM(no_of_hours) as regularHours FROM `cp_timesheet_details` td WHERE td.`hours_type` = 'Regular Hour' AND td.`timesheet_id` = ".$model->id." AND td.creation_day='".$todayDate."'";
                      $timesheet_detail = Yii::app()->db->createCommand($timesheet_detail_q)->queryRow();
  
                      if($timesheet_detail['regularHours'] > 0){
                          $todayExistingRHours = $timesheet_detail['regularHours'];
                      }
  
                      if ($hours <= $allowedRegHours) {
                          if($perDayCheck == true && $todayExistingRHours+$value > $perDayAllowedHours && $model->total_regular_hours < $allowedRegHours){
                              if($todayExistingRHours > 0){
                                  $noOfHours = $perDayAllowedHours - $todayExistingRHours;
                              }else{
                                  $noOfHours = $perDayAllowedHours;
                              }
  
                              $model->total_regular_hours += $noOfHours;
                              $model->save(false);
  
                              $postedValue = array(
                                  'creation_day' => $key1,
                                  'project' => $project_code_id,
                                  'notes' => $_POST['notes'][$key1][$key],
                                  'leave_reason' => $leaveReason
                              );
                              self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, 'Regular Hour', $noOfHours,$i);
  
                              if($todayExistingRHours > 0){
                                  $noOfHours = $value - $perDayAllowedHours + $todayExistingRHours;
                              }else{
                                  $noOfHours = $value - $perDayAllowedHours;
                              }
  
                              $model->total_overtime_hours += $noOfHours;
                              $model->save(false);
  
                              $postedValue = array(
                                  'creation_day' => $key1,
                                  'project' => $project_code_id,
                                  'notes' => $_POST['notes'][$key1][$key],
                                  'leave_reason' => $leaveReason
                              );
                              self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, 'Over Time', $noOfHours,$i);
                              $perDayAllowedRemainingHours = 0;
  
                          }else{
  
                              $model->total_regular_hours += $value;
                              $model->save(false);
                              $noOfHours = $value;
                              $postedValue = array(
                                  'creation_day' => $key1,
                                  'project' => $project_code_id,
                                  'notes' => $_POST['notes'][$key1][$key],
                                  'leave_reason' => $leaveReason
                              );
                              self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, 'Regular Hour', $noOfHours,$i);
                              $perDayAllowedRemainingHours = $perDayAllowedRemainingHours - $value;
                          }
  
                      } else {
                          //if regular hours are completed and everything is not OT.
                          if($model->total_regular_hours >= $allowedRegHours){
                              $sub_hour_type = 'Over Time';
                              if ($sub_hour_type == 'Over Time') {
                                  $model->total_overtime_hours += $value;
                                  if (true) {
                                      $noOfHours = $value;
                                      $postedValue = array(
                                          'creation_day' => $key1,
                                          'project' => $project_code_id,
                                          'notes' => $_POST['notes'][$key1][$key],
                                          'leave_reason' => $leaveReason
                                      );
                                      self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
                                  }
                              }
                          }else{
                              $sub_hour_type = 'Regular Hour';
                              $e_hours = $hours - $allowedRegHours;
                              $r_hours = $value - $e_hours;
  
                              if($perDayCheck == true && $r_hours + $todayExistingRHours > $perDayAllowedHours){
                                  $diff = $r_hours + $todayExistingRHours - $perDayAllowedHours;
                                  $e_hours += $diff;
                                  $r_hours = $r_hours - $diff;
                              }
  
                              if($r_hours > 0){
                                  $model->total_regular_hours += $r_hours;
                                  if (true) {
                                      $model->save(false);
                                      $noOfHours = $r_hours;
                                      $postedValue = array(
                                          'creation_day' => $key1,
                                          'project' => $project_code_id,
                                          'notes' => $_POST['notes'][$key1][$key],
                                          'leave_reason' => $leaveReason
                                      );
                                      self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
                                  }
                              }
  
                              if($e_hours > 0){
                                  $sub_hour_type = 'Over Time';
                                  if ($sub_hour_type == 'Over Time') {
                                      $model->total_overtime_hours += $e_hours;
                                      if (true) {
                                          $noOfHours = $e_hours;
                                          $postedValue = array(
                                              'creation_day' => $key1,
                                              'project' => $project_code_id,
                                              'notes' => $_POST['notes'][$key1][$key],
                                              'leave_reason' => $leaveReason
                                          );
                                          self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
                                      }
                                  }
                              }
                          }
                          $model->save(false);
                      }
                      $i++;
                  }
  
                  //calculating number of hours.
                  $model->total_hours = $model->total_regular_hours + $model->total_overtime_hours + $model->total_doubletime_hours;
                  $model->new_total_hours = $model->total_hours;
                  /*Converting all the hours on the scale of hundard*/
                  $model->total_100scale_regular_hours = $model->total_regular_hours;
                  $model->total_100scale_overtime_hours = $model->total_overtime_hours;
                  $model->total_100scale_doubletime_hours = $model->total_doubletime_hours;
                  $model->total_100scale_hours = $model->total_hours;
                  $model->new_100scale_total_hours = $model->new_total_hours;
                  /*Converting all the hours on the scale of hundard*/
  
                  $model->save(false);
              }
          }
          //exit;
      }

    public static function findContractRate($endDate, $contractID)
    {
        // Format the date
        $formattedEndDate = date('Y-m-d', strtotime($endDate));

        // Query to get the maximum rate ID
        $contractRateID = DB::table('contract_rates')
            ->where('effective_date', '<=', $formattedEndDate)
            ->where('contract_id', $contractID)
            ->orderByDesc('id')
            ->value(DB::raw('MAX(id)'));

        // Retrieve the ContractRate model based on the rate ID
        $contractRate = ContractRate::where('id', $contractRateID)->orderByDesc('id')->first();

        return $contractRate;
    }

    public static function dateIsInBetween($from, $to, $date)
    {
        return date('Ymd', strtotime($date)) >= date('Ymd', strtotime($from)) && date('Ymd', strtotime($date)) <= date('Ymd', strtotime($to)) ? true : false;
    }
    
}
?>
