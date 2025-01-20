<?php
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\GenericData;
use App\Models\Location;
use App\Models\SettingCategory;
use App\Models\Consultant;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\CareerOpportunitySubmission;
use App\Models\CareerOpportunitiesContract;
use App\Models\CareerOpportunitiesInterview;
use App\Models\CareerOpportunitiesOffer;
use App\Models\OfferWorkflowApproval;
use App\Models\CareerOpportunitiesWorkorder;
use App\Models\VendorTeammember;
use Stichoza\GoogleTranslate\GoogleTranslate;

function userType(){
    $array = array(
        '1' => 'Admin',
        '2' => 'Client',
        '3' => 'Vendor',
        '4' => 'Consultant'
    );
    return $array;
}

function formType() {
    $array = array(
        '1' => 'Job',
        '2' => 'Submission',
        '3' => 'Offer'
    );
    return $array;
}


function userRoles(){
    $array = array(
        '1' => 'Branch Manager',
        '2' => 'Regional HR',
        '3' => 'Regional CFO',
        '4' => 'Regional President',
        '5' => 'Divisional CFO'
    );
    return $array;
}



function currency(){
    $curr = array(
        '1' => '$',
        '2' => '€',
        '3' => '£',
    );
    return $curr;
}

if (!function_exists('getSettingTitleById')) {
    function getSettingTitleById($id)
    {
        $setting = \App\Models\Setting::where('id', $id)
            ->where('status', 'active') // Check for active status
            ->first(); // Use first() to get a single record
        return $setting ? $setting->title : 'N/A';
    }
}
if (!function_exists('getGenericTitleById')) {
    function getGenericTitleById($id)
    {
        $setting = \App\Models\GenericData::where('id', $id)
            ->where('status', 'active') // Check for active status
            ->first(); // Use first() to get a single record

        return $setting ? $setting->name : 'N/A';
    }
}
if (!function_exists('getActiveRoles')) {
    function getActiveRoles(User $user): array
    {


        $activeRoles = [];

        // Check each role's status in its respective table
        if ($user->is_admin) {
            $admin = $user->admin; // Assuming a relationship is defined
            if ($admin && $admin->admin_status == 1) {
                $activeRoles['admin'] = true;
            }
        }

        if ($user->is_client) {
            $client = $user->client; // Assuming a relationship is defined
            if ($client && $client->profile_status == 1) {
                $activeRoles['client'] = true;
            }
        }

        if ($user->is_vendor) {
            $vendor = $user->vendor;
            if ($vendor && $vendor->profile_status == 1) {
                $activeRoles['vendor'] = true;
            }
        }

        if ($user->is_consultant) {
            $consultant = $user->consultant; // Assuming a relationship is defined
            if ($consultant && $consultant->profile_status == 1) {
                $activeRoles['consultant'] = true;
            }
        }
        return $activeRoles;
    }
}

if (!function_exists('redirectToDashboard')) {
    /**
     * Determine the appropriate dashboard route based on the role.
     *
     * @param string|null $role
     * @return string
     */
    function redirectToDashboard($role = null)
    {
        switch ($role) {
            case 'admin':
                return 'admin.dashboard';
            case 'client':
                return 'client.dashboard';
            case 'vendor':
                return 'vendor.dashboard'; // Adjust this based on your role setup
            default:
                return 'home';
        }
    }
}
if (!function_exists('getActiveRecordsByType')) {
 /**
     * Get active records by type.
     *
     * @param string $type
     * @return \Illuminate\Support\Collection
     */
    function getActiveRecordsByType($type)
    {
        return GenericData::byTypeAndStatus($type);
    }
}


if (!function_exists('checksetting')) {
    function checksetting($id) {

        $category = SettingCategory::findOrFail($id);
        $settings = [];
        // dd($category);
        if(isset($category->settings)) {
            $settings = $category->settings()->where('status', 'active')->pluck('title','id');
        }

        return $settings;
    }
}

if (!function_exists('locationName')) {
    function locationName($id) {

        $location_query = Location::findOrFail($id);


        return $location_query->name . '-' . $location_query->address1 . '-' . $location_query->city . '-' . $location_query->state->name . '-' . $location_query->zip_code;
    }
}

if (!function_exists('removeComma')) {
    function removeComma($number)
    {
        return str_replace(',', '', $number);
    }
}

if (!function_exists('checkUserId')) {
    function checkUserId($userid,$sessionrole)
    {
        if ($sessionrole == "Admin") {
            $userid = Admin::getAdminIdByUserId($userid);
        } elseif ($sessionrole == "Client") {
            $userid = Client::getClientIdByUserId($userid);
        } elseif ($sessionrole == "Vendor") {
            $userid = Vendor::getVendorIdByUserId($userid);
        } elseif ($sessionrole == "Consultant") {
            $userid = Consultant::getConsultantIdByUserId($userid);
        }
        return $userid;
    }
}

if (!function_exists('numberOfWorkingDays')) {
    // Helper method to calculate the number of working days between two dates
    function numberOfWorkingDays($startDate, $endDate)
    {
        $endDate = strtotime($endDate);
        $startDate = strtotime($startDate);
        $days = ($endDate - $startDate) / 86400 + 1; // Convert difference to days
        $no_full_weeks = floor($days / 7); // Full weeks
        $no_remaining_days = fmod($days, 7); // Remaining days

        $the_first_day_of_week = date("N", $startDate); // Day of the week for start date
        $the_last_day_of_week = date("N", $endDate); // Day of the week for end date

        // If the first and last days are in the same week
        if ($the_first_day_of_week <= $the_last_day_of_week) {
            if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--; // Saturday
            if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--; // Sunday
        } else {
            if ($the_first_day_of_week == 7) { // If starting on a Sunday
                $no_remaining_days--;
                if ($the_last_day_of_week == 6) { // If ending on a Saturday
                    $no_remaining_days--;
                }
            } else {
                $no_remaining_days -= 2; // Adjust for weekends
            }
        }

        $workingDays = $no_full_weeks * 5; // Full weeks * 5 working days
        if ($no_remaining_days > 0) {
            $workingDays += $no_remaining_days; // Add remaining working days
        }

        return round($workingDays); // Return the rounded number of working days
    }
}

    if (!function_exists('handleFileUpload')) {
        function handleFileUpload($request, $fileKey, $directory)
        {
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                $filename = md5(rand(1000, 9999) . time()) . '.' . $file->getClientOriginalExtension();
                $file->storeAs($directory, $filename, 'public');
                return $filename;
            }
            return null;
        }
    }
    if (!function_exists('generateUniqueUserCode')) {
        function generateUniqueUserCode($length = 10)
        {
            do {
                // Generate a random string
                $uniqueCode = Str::random($length);
            } while (Consultant::where('unique_id', $uniqueCode)->exists());

            return $uniqueCode;
        }
    }

    if (!function_exists('formatDate')) {
        function formatDate($date)
        {
            return Carbon::parse($date)->format('m/d/Y');
        }
    }
    if (!function_exists('formatDateTime')) {
        function formatDateTime($date)
        {
            return Carbon::parse($date)->format('m/d/Y h:i A');
        }
    }

if (!function_exists('updateSubmission')) {
    function updateSubmission($model,$type){


       $userid =  checkUserId(\Auth::id(),session('selected_role'));
        $submission = CareerOpportunitySubmission::whereNotIn('resume_status', [6, 11])
            ->where('career_opportunity_id', $model->id)
            ->get();

        foreach($submission as $sValue){
            $contract = CareerOpportunitiesContract::where('submission_id', $sValue->id)->first();
            if($type == 'Filled'){
                $reason_for_rejection = 66;
            }elseif($type == 'Closed'){
                $reason_for_rejection = 66;
            }else{
                $reason_for_rejection = 66;
            }
            if($contract===null){
                $submissionUpdate = CareerOpportunitySubmission::findOrFail($sValue->id);
                $submissionUpdate->resume_status = 6;
                $submissionUpdate->reason_for_rejection = $reason_for_rejection;
                $submissionUpdate->date_rejected = now();
                $submissionUpdate->rejected_by = $userid;
                $submissionUpdate->rejected_type = 1;
                $submissionUpdate->save();

                // Update Interview
                $allInterviews = CareerOpportunitiesInterview::where('submission_id', $sValue->id)->get();
                foreach($allInterviews as $interview){
                    $reason_for_rejection = 66;
                    $interviewModel = CareerOpportunitiesInterview::findOrFail($interview->id);
                    $interviewModel->reason_rejection = $reason_for_rejection;
                    $interviewModel->notes = '';
                    $interviewModel->status = 3;
                    $interviewModel->rejected_by = $userid;
                    $interviewModel->rejected_type = 1;
                    $interviewModel->interview_cancellation_date = now(); //Interview cancellation date.
                    $interviewModel->save();
                }

                //Update Offer
                $allOffers = CareerOpportunitiesOffer::where('submission_id', $sValue->id)->get();
                $reason_for_rejection = 66;
                foreach($allOffers as $offerModel){
                    $offerModel->status = 2;
                    $offerModel->reason_for_rejection = $reason_for_rejection;
                    $offerModel->notes = '';
                    $offerModel->modified_by_id = $userid;
                    $offerModel->date_modified = now();
                    $offerModel->offer_rejection_date = now();
                    $offerModel->modified_by_type = 1;
                    $offerModel->save();

                    //update offer workflow

                    $allapprovals = OfferWorkflowApproval::where('offer_id', $offerModel->id)->get();

                    if($allapprovals){
                        $reason_for_rejection = 66;
                        foreach ($allapprovals as $approval){
                            $approval->status = 'Rejected';
                            $approval->rejection_reason = $reason_for_rejection;
                            $approval->status_time = now();
                            $approval->approve_reject_by = $userid;
                            $approval->approve_reject_type = 'admin';
                            $approval->ip_address = request()->ip();
                            $approval->save();
                        }
                    }

                }
                $allWorkorder = CareerOpportunitiesWorkorder::where('submission_id', $sValue->id)->get();
                foreach($allWorkorder as $workOrderModel){
                    $reason_for_rejection =  66;
                    $workOrderModel->status = 2;
                    $workOrderModel->rejection_date =  now();
                    $workOrderModel->modified_by_id=  $userid;
                    $workOrderModel->modified_by_type = 1;
                    $workOrderModel->rejection_reason= $reason_for_rejection;
                    $workOrderModel->save();
                }

            }
        }
        return true;
    }
}

if (!function_exists('jobVendSubmissionLimit')) {
    function jobVendSubmissionLimit($jobID){

        $vendorID =  checkUserId(\Auth::id(),session('selected_role'));

        $vendorID = superVendor($vendorID);
        $submissionCount = CareerOpportunitySubmission::where('career_opportunity_id', $jobID)
        ->where('vendor_id', $vendorID)
        ->whereNotIn('resume_status', [11])
        ->count();
    // dd($submissionCount);
        return $submissionCount;
    }
}

if (!function_exists('SuperVendor')) {
    function superVendor($mainVendor){
        $vendorID = $mainVendor;


        $teamMemData = VendorTeammember::where('teammember_id',$vendorID)->first();
        if($teamMemData !=null){
        $vendorID = $teamMemData->vendor_id;
        }

        return $vendorID;
    }
}
if (!function_exists('getMiscContractEndDate')) {
    function getMiscContractEndDate($contractID){
        $contract = CareerOpportunitiesContract::findOrFail($contractID);
        $workorder = CareerOpportunitiesWorkorder::findOrFail($contract->workorder_id);
        // $extensionReq = ContractExtensionReq::model()->findByAttributes(array('contract_id' => $contractID,'ext_vendor_approval'=>2),array('order'=>'id desc'));

        if($contract->termination_status == 2 || $contract->status == 3){
            $contract_end_date = $contract->termination_date;
        }
        // elseif ($extensionReq){
        //     $contract_end_date = $extensionReq->new_contract_end_date;
        // }
        else{
            $contract_end_date = $contract->end_date;
        }
        return formatDate($contract_end_date);
    }
}

if (!function_exists('updateContractReason')) {
    function updateContractReason(){
        return array(
            '1' => 'Additional Budget',
            '5' => 'Update Start Date',
            '6' => 'Assignment Termination',
            //'9' => 'Cost Center Rate Change', // used for cost center rate change
            //'10' => 'New Cost Center Added', // used for new cost center addition.
            '2' => 'Extension',
            '3' => 'Rate Change',
            // '7' => 'Update Job Level',
            '4' => 'Non-Financial Change',
        );
    }
}
if (!function_exists('timesheetGetStartAndEndDate')) {
    function timesheetGetStartAndEndDate($week, $year, $startDay="")
    {
        if($startDay == ""){
            $startDay = 0;
        }
        //$startDay = 1; // 0 means it will start from sunday. 1 means Monday
        $day_of_week = date('m/d/Y', strtotime('Monday'));
        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7*$week)+ $startDay -$day)*24*3600;
        /*if($year == 2023){
            $time += ((7*($week-1))+ $startDay -$day)*24*3600;
        }else{
            $time += ((7*$week)+ $startDay -$day)*24*3600;
        }*/

        $return[0] = date('Y-n-j', $time);
        $time += 6*24*3600;
        $return[1] = date('Y-n-j', $time);
        return $return;
    }
}
if (!function_exists('translate')) {
    function translate($text)
    {
        $locale = session('locale', 'en');
        App::setLocale($locale);
        if ($locale === 'en') {
            return $text;
        }
        $cacheKey = "translation_{$locale}_" . md5($text);
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($text, $locale) {
            try {
                return GoogleTranslate::trans($text, $locale);
            } catch (Exception $e) {
                return $text;
            }
        });
    }
}







