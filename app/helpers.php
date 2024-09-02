<?php
use App\Models\User;
use App\Models\GenericData;
use App\Models\SettingCategory;

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
            $vendor = $user->vendor; // Assuming a relationship is defined
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
        $settings =  $category->settings()->where('status', 'active')->pluck('title','id');
        return $settings;
    }
}



