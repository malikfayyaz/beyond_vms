<?php
namespace App\Services;
use Illuminate\Support\Facades\DB;
use App\Models\TimesheetProject;
use App\Models\ContractRate;
use App\Models\CpTimesheet;
use App\Models\CpTimesheetDetail;
use App\Models\TimesheetCostcenterTemp;
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

    public static function timesheetProCalc($timesheetID,$invoiceStartDate,$invoiceEndDate,$contract,$candidate,$loginUser,$workorderID,$offerID,$clientID,$approver,$locationID,$timesheetDuration,$portal,$oldModelID="",$rejectedParent="0",$invoiceStartDateDisplay="",$invoiceEndDateDisplay="",$request){

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
  
        //   echo '<pre>';print_r($result['days']);exit;
  
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
              $modelOldData->save();
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
            $key1 = date('Y-m-d', strtotime($key1));
            // dd($key1);
              if (!self::dateIsInBetween($contract->start_date, $contract->end_date, $key1)) continue;
  
            //   $timecard_sub_config = UtilityManager::timeCardSubConfiguration();
            //   $timesheetSubTypeId =  $model->sub_type_of_timesheet;
              if(  strtolower($jobData->payment_type) == 34){
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
                      $model->sub_type_of_timesheet = $jobData->payment_type;
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
                          $model->cd_memo_type = $request->input('cd_memo_type');
                          $model->cd_memo_reason = $request->input('cd_memo_reason');
                          $model->cd_memo_notes = $request->input('cd_memo_notes');
                          $model->rate_type_for_modified = $request->input('rate_selection');
                      }
  
                      $model->save();
  
                      if($perDayCheck == true && $value > $perDayAllowedHours){
                          $noOfHours = $model->total_regular_hours;
                          $postedValue = array(
                              'creation_day' => $key1,
                              'project' => $project_code_id,
                              'notes' => $request->input("notes.{$key1}.{$key}"),
                              'leave_reason' => $leaveReason
                          );
                          self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
  
                          $i++;
  
                          $sub_hour_type = 'Over Time';
                          $noOfHours = $model->total_overtime_hours;
                          $postedValue = array(
                              'creation_day' => $key1,
                              'project' => $project_code_id,
                              'notes' => $request->input("notes.{$key1}.{$key}"),
                              'leave_reason' => $leaveReason
                          );
                          self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
  
                          $perDayAllowedRemainingHours = 0;
                      }else{
                          $noOfHours = $value;
                          $postedValue = array(
                              'creation_day' => $key1,
                              'project' => $project_code_id,
                              'notes' => $request->input("notes.{$key1}.{$key}"),
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
                      $model->save();
  
                      //if already record exist in main table then this portion will run.
                      $hours = $model->total_regular_hours + $value;
  
                      //current day date.
                      $todayDate = $key1;
                      $timesheet_detail = DB::table('cp_timesheet_details')
                      ->selectRaw('SUM(no_of_hours) as regularHours')
                      ->where('hours_type', 'Regular Hour')
                      ->where('timesheet_id', $model->id)
                      ->where('creation_day', $todayDate)
                      ->first();
  
                      if($timesheet_detail->regularHours > 0){
                          $todayExistingRHours = $timesheet_detail->regularHours;
                      }
  
                      if ($hours <= $allowedRegHours) {
                          if($perDayCheck == true && $todayExistingRHours+$value > $perDayAllowedHours && $model->total_regular_hours < $allowedRegHours){
                              if($todayExistingRHours > 0){
                                  $noOfHours = $perDayAllowedHours - $todayExistingRHours;
                              }else{
                                  $noOfHours = $perDayAllowedHours;
                              }
  
                              $model->total_regular_hours += $noOfHours;
                              $model->save();
  
                              $postedValue = array(
                                  'creation_day' => $key1,
                                  'project' => $project_code_id,
                                  'notes' => $request->input("notes.{$key1}.{$key}"),
                                  'leave_reason' => $leaveReason
                              );
                              self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, 'Regular Hour', $noOfHours,$i);
  
                              if($todayExistingRHours > 0){
                                  $noOfHours = $value - $perDayAllowedHours + $todayExistingRHours;
                              }else{
                                  $noOfHours = $value - $perDayAllowedHours;
                              }
  
                              $model->total_overtime_hours += $noOfHours;
                              $model->save();
  
                              $postedValue = array(
                                  'creation_day' => $key1,
                                  'project' => $project_code_id,
                                  'notes' => $request->input("notes.{$key1}.{$key}"),
                                  'leave_reason' => $leaveReason
                              );
                              self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, 'Over Time', $noOfHours,$i);
                              $perDayAllowedRemainingHours = 0;
  
                          }else{
  
                              $model->total_regular_hours += $value;
                              $model->save();
                              $noOfHours = $value;
                              $postedValue = array(
                                  'creation_day' => $key1,
                                  'project' => $project_code_id,
                                  'notes' => $request->input("notes.{$key1}.{$key}"),
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
                                          'notes' => $request->input("notes.{$key1}.{$key}"),
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
                                      $model->save();
                                      $noOfHours = $r_hours;
                                      $postedValue = array(
                                          'creation_day' => $key1,
                                          'project' => $project_code_id,
                                          'notes' => $request->input("notes.{$key1}.{$key}"),
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
                                              'notes' => $request->input("notes.{$key1}.{$key}"),
                                              'leave_reason' => $leaveReason
                                          );
                                          self::timesheetProSaveDetails($model, $contract, $loginUser, $postedValue, $sub_hour_type, $noOfHours,$i);
                                      }
                                  }
                              }
                          }
                          $model->save();
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
  
                  $model->save();
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
            ->value('id');
       

        // Retrieve the ContractRate model based on the rate ID
        $contractRate = ContractRate::where('id', $contractRateID)->orderByDesc('id')->first();

        return $contractRate;
    }

    public static function dateIsInBetween($from, $to, $date)
    {
       
        return date('Ymd', strtotime($date)) >= date('Ymd', strtotime($from)) && date('Ymd', strtotime($date)) <= date('Ymd', strtotime($to)) ? true : false;
    }

    public static function timesheetProSaveDetails($model,$contract,$loginUser,$postedValue,$sub_hour_type,$noOfHours,$i){
        //$timesheetCCConfig = TimesheetProject::model()->findByPk($postedValue['project']);
        //$costCenterSetting = TimesheetProject::model()->findByAttributes(array('contract_id'=>$model->contract_id,'status'=>1));

        //$hoursScaleOfHoundard = self::convertToScaleOfHundard($noOfHours);
        //$project_code_id = $costCenterSetting->costcenter_id;
        $project_code_id = $postedValue['project'];
        $leave_reason = $postedValue['leave_reason'];
        if(true /*$i == 1 || $noOfHours > 0*/){
            $model1 = new CpTimesheetDetail;
            $model1->timesheet_id = $model->id;
            $model1->contract_id = $contract->id;
            $model1->candidate_id = $loginUser;
            $model1->client_config_type = 2;
            $model1->creation_day  = $postedValue['creation_day'];
            $model1->project_code_id  = $project_code_id;
            $model1->project_task_type  = 'Cost Center';
            $model1->comment  = $postedValue['notes'];
            $model1->hours_type = $sub_hour_type;
            $model1->no_of_hours = $noOfHours;
            //converting hours on the scale of hundard.
            $model1->no_100scale_of_hours = $noOfHours;
            $model1->leave_reason = $leave_reason;
           
            if($model1->save()){
            
                if(true /*$i == 1 || $model1->no_of_hours > 0*/){
                    $costcenter_hours  =  new TimesheetCostcenterTemp;
                    //$costcenter_hours->cost_center = $timesheetCCConfig->costcenter_id;
                    //$costcenter_hours->cost_center = $project_code_id;
                    $costcenter_hours->cost_center = 0; //for now we will not be saving the BU here.
                    $costcenter_hours->hours = $model1->no_of_hours;
                    $costcenter_hours->type = $model1->hours_type;
                    $costcenter_hours->timesheet_id = $model->id;
                    $costcenter_hours->creation_day = $model1->creation_day;
                    $costcenter_hours->notes = $model1->comment;
                    $costcenter_hours->leave_reason = $leave_reason;
                    $costcenter_hours->save();
                }
            }
        }

        //echo $i.'<br />';
    }

    public static function calculateTaxesOnSubmissionOfTimesheet($model,$portal){
        echo '<pre>';print_r($model);exit;

        // $timecard_sub_config = UtilityManager::timeCardSubConfiguration();
        // $timesheetSubTypeId =  $model->sub_type_of_timesheet;
        $sub_type_of_timesheet = 34;

        $approvalLocation = Location::model()->findByPk($model->location_id);

        if( $model->sub_type_of_timesheet == 34){
            $sub_type_of_timesheet = 34;
        }elseif( $model->sub_type_of_timesheet == 35){
            $sub_type_of_timesheet = 35;
        }

        $model->total_regular_payrate = 0;
        $model->total_overtime_payrate = 0;
        $model->total_doubletime_payrate = 0;
        $model->total_regular_billrate = 0;
        $model->total_overtime_billrate = 0;
        $model->total_doubletime_billrate = 0;
        $model->total_payrate = 0;
        $model->new_total_payrate = 0;
        $model->total_billrate = 0;
        $model->new_totall_billrate = 0;
        $model->save();

    

        $invoiceEndDate = date('Y-m-d',strtotime($model->invoice_end_date));
        $costcenter_hours = TimesheetCostcenterTemp::where('timesheet_id', $model->id)
        ->where('cost_center', 0)
        ->get();

        $assignedBU = TimesheetProject::where('contract_id', $model->contract_id)
        ->where('status', 1)
        ->get();
       
        $i=0;
        $empty=true;
        $data = [];
        foreach ($assignedBU as $buKey=>$timesheeetBU){
            $buID = $timesheeetBU->id;
            $buPercentage = $timesheeetBU->budget_percentage/100;
            //now moving data towards BU according to there percentage.
            foreach($costcenter_hours as $hoursKey=>$hoursData) {
                $i++;
                if($hoursData->hours > 0){
                    $empty=false;
                }
                $data[$buID][$i] = [
                    'buID' => $buID,
                    'hours'=> $buPercentage*$hoursData->hours,
                    'type'=> $hoursData->type,
                    'timesheetId'=>  $model->id,
                    'creationDay'=> $hoursData->creation_day,
                    'notes'=> $hoursData->notes,
                    'leaveReason'=>  $hoursData->leave_reason,
                ];
            }
        }
        foreach ($data as $key => $value) {
            foreach ($value as  $row) {
                if(!$empty)
                {
                    if($row['hours'] > 0)
                    {
                        self::saveTimesheetCostcenterTempTable($row);
                    }
                }
                else{
                    self::saveTimesheetCostcenterTempTable($row);   
                    break;
                }
            } 
        }
        TimesheetCostcenterTemp::where('timesheet_id', $model->id)
        ->where('cost_center', 0)
        ->delete();
    
        // Find all records matching the conditions
        $costcenter_hours = TimesheetCostcenterTemp::where('timesheet_id', $model->id)->get();
        

        //echo '<pre>';print_r($costcenter_hours);exit;

        $contract = Contract::model()->findByPk($model->contract_id);
        $workorder = Workorder::model()->findByPk($contract->workorder_id);
        $jobModel = Job::model()->findByPk($contract->job_id);

        $taxPercentage = $workorder->location_tax;
        $mspPercentage = $workorder->msp_per;
        //$mspPenalityPercentage = self::mspPenalityFee($jobModel);
        $mspPenalityPercentage = $workorder->msp_per;

        //deleting all the old detaills of timesheet.
        CpTimesheetDetails::where('timesheet_id', $model->id)->delete();

        // Delete all CpTimesheetTaxAmount records with the given timesheet_id
        CpTimesheetTaxAmount::where('timesheet_id', $model->id)->delete();

        $contractRate =  self::findContractRate($invoiceEndDate,$contract->id);

        $incrementalVar = 1;
        foreach($costcenter_hours as $data) {

            $currentProject = TimesheetProject::findOrFail($data->cost_center);

            //if its modified timesheet and its not rate change then use old timesheet rates.
            if ($model->parent_id != 0 && $model->rate_type_for_modified != 2) {
                //$oldtaxModel = TimesheetTaxAmount::model()->findByAttributes(array('timesheet_id' => $model->parent_id, 'cost_center_config' => $data->cost_center));
                $oldTaxModel = TimesheetTaxAmount::where('timesheet_id', $model->parent_id)->first();
                //candidate payrate data
                $regular_payrate = $oldtaxModel->regular_payrate;
                $overtime_payrate = $oldtaxModel->overtime_payrate;
                $doubletime_payrate = $oldtaxModel->doubletime_payrate;

                //client billrate data
                $regular_billrate = $oldtaxModel->regular_billrate;
                $overtime_billrate = $oldtaxModel->overtime_billrate;
                $doubletime_billrate = $oldtaxModel->doubletime_billrate;

                //vendor billrate data
                $vendor_regular_billrate = $oldtaxModel->regular_vendor_billrate;
                $vendor_overtime_billrate = $oldtaxModel->overtime_vendor_billrate;
                $vendor_doubletime_billrate = $oldtaxModel->doubletime_vendor_billrate;

                /*$marking_billrate = $model->marking_billrate;
                $marking_vendor_billrate = $model->marking_vendor_billrate;
                $marking_payrate = $model->marking_payrate;*/

            } else {
                //candidate payrate data
                $regular_payrate = $contractRate->candidate_pay_rate;
                $overtime_payrate = $contractRate->candidate_overtime_rate;
                $doubletime_payrate = $contractRate->candidate_doubletime_rate;

                //client billrate data
                $regular_billrate = $contractRate->client_bill_rate;
                $overtime_billrate = $contractRate->client_overtime_rate;
                $doubletime_billrate = $contractRate->client_doubletime_rate;

                //vendor billrate data
                $vendor_regular_billrate = $contractRate->vendor_bill_rate;
                $vendor_overtime_billrate = $contractRate->vendor_overtime_rate;
                $vendor_doubletime_billrate = $contractRate->vendor_doubletime_rate;

                if($currentProject->is_marking_rate == 'Yes'){
                    $regular_payrate = $contractRate->candidate_marking_rate;
                    $regular_billrate = $contractRate->client_marking_rate;
                    $vendor_regular_billrate = $contractRate->vendor_marking_rate;
                }

                /*$marking_billrate = $contractRate->client_marking_rate;
                $marking_vendor_billrate = $contractRate->vendor_marking_rate;
                $marking_payrate = $contractRate->candidate_marking_rate;*/

                $submission = VendorJobSubmission::model()->findByPk($contract->submission_id);

                //If candidate ovetime is exempted than regular rates will be implemented.
                if ($submission->ot_exempt_position == 'Yes'  || $approvalLocation->Country->code == 'UK') {
                    $overtime_payrate = $contractRate->candidate_pay_rate;
                    $overtime_billrate = $contractRate->client_bill_rate;
                    $vendor_overtime_billrate = $contractRate->vendor_bill_rate;

                    $doubletime_payrate = $contractRate->candidate_pay_rate;
                    $doubletime_billrate = $contractRate->client_bill_rate;
                    $vendor_doubletime_billrate = $contractRate->vendor_bill_rate;
                }
            }
            $taxModel = TimesheetTaxAmount::where('timesheet_id', $model->id)
            ->where('cost_center_config', $data->cost_center)
            ->first();
            if (empty($taxModel)) {
                $taxModel = new TimesheetTaxAmount;
                $taxModel->timesheet_id = $model->id;
                //$taxModel->cost_center_config = $timesheetCCConfig->id;
                $taxModel->cost_center_config = $data->cost_center;
                $taxModel->location_id = $workorder->location_id;
                $taxModel->category_id = $jobModel->cat_id;
                $taxModel->msp_percentage = $mspPercentage;
                $taxModel->penality_msp_percentage = $mspPenalityPercentage;
                $taxModel->tax_percentage = $taxPercentage;
                $taxModel->regular_billrate = $regular_billrate;
                $taxModel->overtime_billrate = $overtime_billrate;
                $taxModel->doubletime_billrate = $doubletime_billrate;
                $taxModel->regular_payrate = $regular_payrate;
                $taxModel->overtime_payrate = $overtime_payrate;
                $taxModel->doubletime_payrate = $doubletime_payrate;
                $taxModel->regular_vendor_billrate = $vendor_regular_billrate;
                $taxModel->overtime_vendor_billrate = $vendor_overtime_billrate;
                $taxModel->doubletime_vendor_billrate = $vendor_doubletime_billrate;
                $taxModel->dept_id = $workorder->department_code;
                $taxModel->gl_id = $workorder->gl_code_id;
                $taxModel->cost_center_id = $workorder->wo_cost_center;
                $taxModel->created_at = date('Y-m-d H:i:s');
                $taxModel->save();
            }

            //inserting new all details to the timesheet detail table
            $timesheetDetails = new CpTimesheetDetails;
            $timesheetDetails->timesheet_id = $model->id;
            $timesheetDetails->contract_id = $contract->id;
            $timesheetDetails->candidate_id = $contract->candidate_id;
            $timesheetDetails->client_config_type = 2;
            $timesheetDetails->creation_day = $data->creation_day;
            $timesheetDetails->project_code_id = $data->cost_center;
            $timesheetDetails->project_task_type = 'Cost Center';
            $timesheetDetails->hours_type = $data->type;
            $timesheetDetails->no_of_hours = $data->hours;
            $timesheetDetails->no_100scale_of_hours = $data->hours;
            $timesheetDetails->comment = $data->notes;
            $timesheetDetails->leave_reason = $data->leave_reason;

            //if its holiday then no need to calculate the amounts
            if($timesheetDetails->leave_reason == 0){
                if($sub_type_of_timesheet == 35 && $incrementalVar == 1){
                    $timesheetDetails->total_payrate = $regular_payrate * 1;
                    $timesheetDetails->total_billrate = $regular_billrate * 1;
                    $timesheetDetails->total_vendor_billrate = $vendor_regular_billrate * 1;
                }else if($sub_type_of_timesheet == 34){
                    /*$existingData = CpTimesheetDetails::model()->countByAttributes(array('timesheet_id'=>$model->id,'creation_day'=>$data->creation_day));
                    if($existingData == 0){
                        $timesheetDetails->total_payrate = $regular_payrate * 1;
                        $timesheetDetails->total_billrate = $regular_billrate * 1;
                        $timesheetDetails->total_vendor_billrate = $vendor_regular_billrate * 1;
                    }*/
                    $timesheetDetails->total_payrate = $regular_payrate * $data->hours;
                    $timesheetDetails->total_billrate = $regular_billrate * $data->hours;
                    $timesheetDetails->total_vendor_billrate = $vendor_regular_billrate * $data->hours;
                }else if($sub_type_of_timesheet == 'per hour' || $timesheetSubTypeId == 0){
                    if ($data->type == 'Over Time') {
                        $timesheetDetails->total_payrate = $overtime_payrate * $data->hours;
                        $timesheetDetails->total_billrate = $overtime_billrate * $data->hours;
                        $timesheetDetails->total_vendor_billrate = $vendor_overtime_billrate * $data->hours;
                    } elseif ($data->type == 'Double Time') {
                        $timesheetDetails->total_payrate = $doubletime_payrate * $data->hours;
                        $timesheetDetails->total_billrate = $doubletime_billrate * $data->hours;
                        $timesheetDetails->total_vendor_billrate = $vendor_doubletime_billrate * $data->hours;
                    } else {
                        $timesheetDetails->total_payrate = $regular_payrate * $data->hours;
                        $timesheetDetails->total_billrate = $regular_billrate * $data->hours;
                        $timesheetDetails->total_vendor_billrate = $vendor_regular_billrate * $data->hours;
                    }
                }
            }

            $timesheetDetails->save();
            $incrementalVar++;
        }
        $allTaxModels = TimesheetTaxAmount::where('timesheet_id', $model->id)->get();
        $loop = 1;
        foreach($allTaxModels as $singleTaxModel){

            //picking regular hours calculations of rates
            $detailRHoursData = DB::table('cp_timesheet_details')
                ->selectRaw('SUM(total_payrate) as totalRPayrate, SUM(total_billrate) as totalRBillrate, SUM(total_vendor_billrate) as totalRVBillrate, SUM(no_100scale_of_hours) as totalRHours100Scale')
                ->where('hours_type', 'Regular Hour')
                ->where('leave_reason', 0)
                ->where('timesheet_id', $model->id)
                ->where('project_code_id', $singleTaxModel->cost_center_config)
                ->first();

            // Overtime Hours Calculation
            $detailOHoursData = DB::table('cp_timesheet_details')
                ->selectRaw('SUM(total_payrate) as totalOPayrate, SUM(total_billrate) as totalOBillrate, SUM(total_vendor_billrate) as totalOVBillrate, SUM(no_100scale_of_hours) as totalOHours100Scale')
                ->where('hours_type', 'Over Time')
                ->where('leave_reason', 0)
                ->where('timesheet_id', $model->id)
                ->where('project_code_id', $singleTaxModel->cost_center_config)
                ->first();

            // Double Time Hours Calculation
            $detailDHoursData = DB::table('cp_timesheet_details')
                ->selectRaw('SUM(total_payrate) as totalDPayrate, SUM(total_billrate) as totalDBillrate, SUM(total_vendor_billrate) as totalDVBillrate, SUM(no_100scale_of_hours) as totalDHours100Scale')
                ->where('hours_type', 'Double Time')
                ->where('leave_reason', 0)
                ->where('timesheet_id', $model->id)
                ->where('project_code_id', $singleTaxModel->cost_center_config)
                ->first();

            //now saving the rates calculation to the timesheet main table.
            $model->total_regular_payrate += $detailRHoursData->totalRPayrate;
            $model->total_overtime_payrate += $detailOHoursData->totalOPayrate;
            $model->total_doubletime_payrate += $detailDHoursData->totalDPayrate;
            $model->total_regular_billrate += $detailRHoursData->totalRBillrate;
            $model->total_overtime_billrate += $detailOHoursData->totalOBillrate;
            $model->total_doubletime_billrate += $detailDHoursData->totalDBillrate;
            $model->total_payrate = $model->total_regular_payrate + $model->total_overtime_payrate + $model->total_doubletime_payrate;
            $model->new_total_payrate = $model->total_payrate;
            $model->total_billrate = $model->total_regular_billrate + $model->total_overtime_billrate + $model->total_doubletime_billrate;
            $model->new_totall_billrate = $model->total_billrate;
            $model->save();

            //$singleTaxModel = CpTimesheetTaxAmount::model()->findByAttributes(array('timesheet_id'=>$model->id,'cost_center_config'=>$data->cost_center));

            $totalRHours = $detailRHoursData->totalRHours100Scale;
            $totalOHours = $detailOHoursData->totalOHours100Scale;
            $totalDHours = $detailDHoursData->totalDHours100Scale;

            if($sub_type_of_timesheet == 35 && $loop == 1){
                $singleTaxModel->total_regular_billrate = $singleTaxModel->regular_billrate * 1;

                $singleTaxModel->total_regular_payrate = $singleTaxModel->regular_payrate * 1;
                $singleTaxModel->total_payrate = $singleTaxModel->total_regular_payrate + $singleTaxModel->total_overtime_payrate + $singleTaxModel->total_doubletime_payrate;

                $vendorTotalRRate = $singleTaxModel->regular_vendor_billrate * 1;
            }else if($sub_type_of_timesheet == 34 /*&& $loop == 1*/){

                /*$timesheetDetailCountQ = $timesheetCostCenter = CpTimesheetDetails::model()->countByAttributes(array('timesheet_id'=>$model->id),array('group'=>'creation_day'));
                $singleTaxModel->total_regular_billrate = $singleTaxModel->regular_billrate * $timesheetDetailCountQ;
                $singleTaxModel->total_regular_payrate = $singleTaxModel->regular_payrate * $timesheetDetailCountQ;
                $singleTaxModel->total_payrate = $singleTaxModel->total_regular_payrate + $singleTaxModel->total_overtime_payrate + $singleTaxModel->total_doubletime_payrate;
                $vendorTotalRRate = $singleTaxModel->regular_vendor_billrate * $timesheetDetailCountQ;*/

                $singleTaxModel->total_regular_billrate = $singleTaxModel->regular_billrate * $totalRHours;
                $singleTaxModel->total_regular_payrate = $singleTaxModel->regular_payrate * $totalRHours;
                $singleTaxModel->total_payrate = $singleTaxModel->total_regular_payrate + $singleTaxModel->total_overtime_payrate + $singleTaxModel->total_doubletime_payrate;
                $vendorTotalRRate = $singleTaxModel->regular_vendor_billrate * $totalRHours;

            }else if($sub_type_of_timesheet == 'per hour' || $timesheetSubTypeId == 0){
                $singleTaxModel->total_regular_billrate = $singleTaxModel->regular_billrate * $totalRHours;
                $singleTaxModel->total_overtime_billrate = $singleTaxModel->overtime_billrate * $totalOHours;
                $singleTaxModel->total_doubletime_billrate = $singleTaxModel->doubletime_billrate * $totalDHours;

                $singleTaxModel->total_regular_payrate = $singleTaxModel->regular_payrate * $totalRHours;
                $singleTaxModel->total_overtime_payrate = $singleTaxModel->overtime_payrate * $totalOHours;
                $singleTaxModel->total_doubletime_payrate = $singleTaxModel->doubletime_payrate * $totalDHours;
                $singleTaxModel->total_payrate = $singleTaxModel->total_regular_payrate + $singleTaxModel->total_overtime_payrate + $singleTaxModel->total_doubletime_payrate;

                $vendorTotalRRate = $singleTaxModel->regular_vendor_billrate * $totalRHours;
                $vendorTotalORate = $singleTaxModel->overtime_vendor_billrate * $totalOHours;
                $vendorTotalDRate = $singleTaxModel->doubletime_vendor_billrate * $totalDHours;
            }

            if($singleTaxModel->save()){
                if($sub_type_of_timesheet == 35 || $sub_type_of_timesheet == 34){
                    //regular hours tax calculation.
                    self::calculateCalifTaxAmountHelper($singleTaxModel, $taxPercentage, $singleTaxModel->total_regular_billrate, $singleTaxModel->total_regular_payrate,'Regular Hour',$mspPercentage,$vendorTotalRRate);
                }else{
                    //regular hours tax calculation.
                    self::calculateCalifTaxAmountHelper($singleTaxModel, $taxPercentage, $singleTaxModel->total_regular_billrate, $singleTaxModel->total_regular_payrate,'Regular Hour',$mspPercentage,$vendorTotalRRate);

                    //overtime hours tax calculation.
                    self::calculateCalifTaxAmountHelper($singleTaxModel, $taxPercentage, $singleTaxModel->total_overtime_billrate, $singleTaxModel->total_overtime_payrate,'Overtime Hour',$mspPercentage,$vendorTotalORate);

                    //doubletime hours tax calculation.
                    self::calculateCalifTaxAmountHelper($singleTaxModel, $taxPercentage, $singleTaxModel->total_doubletime_billrate, $singleTaxModel->total_doubletime_payrate,'Doubletime Hour',$mspPercentage,$vendorTotalDRate);
                }

            }

            $loop++;
        }

        //finaly changing the status of timesheet to submitted.
        $model->timesheet_sub_status = 1;
        $model->timesheet_status = 0;
        $model->date_submitted = date('Y-m-d H:i:s');
        $model->submitted_from = $portal;

        if($model->total_hours == 0){
            $model->timesheet_status = 1;
            $model->accept_rejected_by = 61;
            $model->approve_date_time = date('Y-m-d H:i:s');
            $model->accept_rejected_by_type = 'Client';
        }
        if(isset($_POST['request_source'])){
            if ($_POST['request_source'] == "integration") {
                $model->timesheet_status = 1;
                $model->accept_rejected_by = 61;
                $model->approve_date_time = date('Y-m-d H:i:s');
                $model->accept_rejected_by_type = 'Client';
            }
        }
        if($model->save()){
            if($model->parent_id != 0){
                $oldModel = CpTimesheet::findOrFail($model->parent_id);
                $oldInvoices = GeneratedInvoice::where('timesheet_id', $oldModel->id)->get();
                foreach($oldInvoices as $oldInvoice){
                    //updating old timesheet and invoice status to deferred.
                    if($oldInvoice->consolidate_invoice_generated != 1) {
                        $oldModel->timesheet_status = 5;
                        $oldModel->save();

                        $oldInvoice->status = 3;
                    }
                    $oldInvoice->not_deduct_budget = 1;
                    $oldInvoice->save();
                }
            }

         
        }

     

    }

    public static function saveTimesheetCostcenterTempTable($data)
    {
        $costcenter_hour  =  new TimesheetCostcenterTemp;
        $costcenter_hour->cost_center = $data['buID'];
        $costcenter_hour->hours = $data['hours'];
        $costcenter_hour->type = $data['type'];
        $costcenter_hour->timesheet_id = $data['timesheetId'];
        $costcenter_hour->creation_day = $data['creationDay'];
        $costcenter_hour->notes = $data['notes'];
        $costcenter_hour->leave_reason = $data['leaveReason'];
        return $costcenter_hour->save();
    }

    public static function calculateCalifTaxAmountHelper($taxModel, $taxPercentage, $totalBillRate, $totalPayRate,$hourType,$mspPercentage,$vendorTotalBillRate){
        $location = Location::findOrFail($taxModel->location_id);

        //client rates and taxes for tax table
        if($hourType == 'Regular Hour'){
            $totalRegularBillRate = $totalBillRate;

            if(isset($location->pa_rule)){
                $regularTax = ($totalRegularBillRate - $totalPayRate) * $taxPercentage/100;
            }else{
                $regularTax = $totalRegularBillRate * $taxPercentage/100;
            }

            //echo $taxModel->total_regular_billrate_tax_amount.'/'.$totalRegularBillRate.'/'.$regularTax;

            //$taxModel->total_regular_billrate += $totalRegularBillRate;
            $taxModel->total_regular_billrate = $totalRegularBillRate;
            $taxModel->total_regular_billrate_tax += $regularTax;
            $taxModel->total_regular_billrate_tax_amount += $totalRegularBillRate + $regularTax;
            $taxModel->total_billrate = $taxModel->total_regular_billrate + $taxModel->total_overtime_billrate + $taxModel->total_doubletime_billrate;
            $taxModel->total_billrate_tax = $taxModel->total_regular_billrate_tax + $taxModel->total_overtime_billrate_tax + $taxModel->total_doubletime_billrate_tax;
            $taxModel->total_billrate_tax_amount = $taxModel->total_regular_billrate_tax_amount + $taxModel->total_overtime_billrate_tax_amount + $taxModel->total_doubletime_billrate_tax_amount;

            //vendor regular hour rates.
            $taxModel->total_regular_vendor_billrate += $vendorTotalBillRate;
            $taxModel->total_vendor_billrate = $taxModel->total_regular_vendor_billrate + $taxModel->total_overtime_vendor_billrate + $taxModel->total_doubletime_vendor_billrate;

            //msp fee
            //$taxModel->msp_regular_fee += number_format($totalRegularBillRate - $vendorTotalBillRate,2);
            $taxModel->msp_regular_fee += number_format(number_format($totalRegularBillRate,2) - number_format($vendorTotalBillRate,2),2);
            $taxModel->msp_total_fee = $taxModel->meal_penality_billrate_fee + $taxModel->paid_break_penality_billrate_fee + $taxModel->msp_regular_fee + $taxModel->msp_overtime_fee + $taxModel->msp_doubletime_fee;

        }else if($hourType == 'Overtime Hour'){
            $totalOvertimeBillRate = $totalBillRate;

            if(isset($location->pa_rule)){
                $overtimeTax = ($totalOvertimeBillRate - $totalPayRate) * $taxPercentage/100;
            }else{
                $overtimeTax = $totalOvertimeBillRate * $taxPercentage/100;
            }

            //$taxModel->total_overtime_billrate += $totalOvertimeBillRate;
            $taxModel->total_overtime_billrate = $totalOvertimeBillRate;
            $taxModel->total_overtime_billrate_tax += $overtimeTax;
            $taxModel->total_overtime_billrate_tax_amount += $totalOvertimeBillRate + $overtimeTax;
            $taxModel->total_billrate = $taxModel->total_regular_billrate + $taxModel->total_overtime_billrate + $taxModel->total_doubletime_billrate;
            $taxModel->total_billrate_tax = $taxModel->total_regular_billrate_tax + $taxModel->total_overtime_billrate_tax + $taxModel->total_doubletime_billrate_tax;
            $taxModel->total_billrate_tax_amount = $taxModel->total_regular_billrate_tax_amount + $taxModel->total_overtime_billrate_tax_amount + $taxModel->total_doubletime_billrate_tax_amount;

            //vendor overtime hour rates.
            $taxModel->total_overtime_vendor_billrate += $vendorTotalBillRate;
            $taxModel->total_vendor_billrate = $taxModel->total_regular_vendor_billrate + $taxModel->total_overtime_vendor_billrate + $taxModel->total_doubletime_vendor_billrate;

            //msp fee
            //$currentMspFee = number_format(number_format($totalOvertimeBillRate,2) - number_format($vendorTotalBillRate,2),2).'===';
            $taxModel->msp_overtime_fee += number_format(number_format($totalOvertimeBillRate,2) - number_format($vendorTotalBillRate,2),2);
            $taxModel->msp_total_fee = $taxModel->meal_penality_billrate_fee + $taxModel->paid_break_penality_billrate_fee + $taxModel->msp_regular_fee + $taxModel->msp_overtime_fee + $taxModel->msp_doubletime_fee;

        }else{
            $totalDoubletimeBillRate = $totalBillRate;

            if(isset($location->pa_rule)){
                $doubleTax = ($totalDoubletimeBillRate - $totalPayRate) * $taxPercentage/100;
            }else{
                $doubleTax = $totalDoubletimeBillRate * $taxPercentage/100;
            }

            //$taxModel->total_doubletime_billrate += $totalDoubletimeBillRate;
            $taxModel->total_doubletime_billrate = $totalDoubletimeBillRate;
            $taxModel->total_doubletime_billrate_tax += $doubleTax;
            $taxModel->total_doubletime_billrate_tax_amount += $totalDoubletimeBillRate + $doubleTax;
            $taxModel->total_billrate = $taxModel->total_regular_billrate + $taxModel->total_overtime_billrate + $taxModel->total_doubletime_billrate;
            $taxModel->total_billrate_tax = $taxModel->total_regular_billrate_tax + $taxModel->total_overtime_billrate_tax + $taxModel->total_doubletime_billrate_tax;
            $taxModel->total_billrate_tax_amount = $taxModel->total_regular_billrate_tax_amount + $taxModel->total_overtime_billrate_tax_amount + $taxModel->total_doubletime_billrate_tax_amount;

            //vendor overtime hour rates.
            $taxModel->total_doubletime_vendor_billrate += $vendorTotalBillRate;
            $taxModel->total_vendor_billrate = $taxModel->total_regular_vendor_billrate + $taxModel->total_overtime_vendor_billrate + $taxModel->total_doubletime_vendor_billrate;

            //msp fee
            $currentMspFee = number_format($totalDoubletimeBillRate - $vendorTotalBillRate,2);
            $taxModel->msp_doubletime_fee += number_format(number_format($totalDoubletimeBillRate,2) - number_format($vendorTotalBillRate,2),2);
            $taxModel->msp_total_fee = $taxModel->meal_penality_billrate_fee + $taxModel->paid_break_penality_billrate_fee + $taxModel->msp_regular_fee + $taxModel->msp_overtime_fee + $taxModel->msp_doubletime_fee;

        }

        $taxModel->save();
        //echo '<pre>';print_r($taxModel);exit;

    }
    
}
?>
