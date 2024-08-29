<?php
use App\Models\User;
use App\Models\GenericData;

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

if (!function_exists('jobLevelsList')) {
   
    function jobLevelsList()
    {
        return [
            '1' => 'Entry Level',
            '2' => 'Mid Level',
            '3' => 'Senior Level',
            '4' => 'Expert Level',
        ];
    }
}

if (!function_exists('currencyList')) {

    function currencyList()
    {
        return [
            '1' => '$ (USD)',
            '2' => '€ (EUR)',
            '3' => '£ (GBP)',
        ];
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
if (!function_exists('workerType')) {
    function workerType() {
        return array(
            '1' => 'Ineligible for Overtime Premium',
            '2' => 'Eligible for Overtime Premium',
        );
    }
}

if (!function_exists('profileWorkerType')) {
    function profileWorkerType() {
        return array(
            '1' => 'CW',
            '2' => 'PWT',
            '3' => 'SOW',
            );
    }
}


if (!function_exists('getTitles')) {
    function getTitles()
    {
        return [
            '1' => 'Admin/Clerical',
            '2' => 'Accounting/Finance',
            '3' => 'Human Resources',
            '4' => 'Technology',
            '5' => 'Marketing/Communication',
            '6' => 'Sales',
            '7' => 'Technical',
            '8' => 'Non-Technical',
            '9' => 'Claims',
            '10' => 'unk',
            '11' => 'Wealth Management',
            '12' => 'Admin',
            '13' => 'Legal',
            '14' => 'Healthcare',
            '15' => 'Safety',
        ];
    }
}
