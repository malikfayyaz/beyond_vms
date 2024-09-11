<?php
use Carbon\Carbon;
use App\Models\User;
use App\Models\GenericData;
use App\Models\Location;
use App\Models\SettingCategory;


 function userType(){
    $array = array(
        '1' => 'Admin',
        '2' => 'Client',
        '3' => 'Vendor',
        '4' => 'Consultant'
    );
    return $array;
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
            if ($vendor && $vendor->status === 'Active') {
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

        $category = SettingCategory::find($id);
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

        $location_query = Location::find($id);
        
            
        return $location_query->name . '-' . $location_query->address1 . '-' . $location_query->city . '-' . $location_query->state->name . '-' . $location_query->zip_code;
    }
}

if (!function_exists('removeComma')) {
    function removeComma($number)
    {
        return str_replace(',', '', $number);
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
}







