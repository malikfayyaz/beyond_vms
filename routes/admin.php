<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{

    Admin\GenericDataController,
    Admin\CatalogController,
    Admin\RatesController,
    Admin\CareerOpportunitiesController,
    Admin\AdminController,
    Admin\AdminManagementController,
    Admin\ClientManagementController,
    Admin\VendorManagementController

};
Route::middleware(['user_role:admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/admin/catalog/{id}', [CatalogController::class, 'view'])->name('admin.catalog.view');

        Route::match(['get', 'post'], 'rates/{type}', [GenericDataController::class, 'manageData'])
        ->name('data.rates')
        ->defaults('fields', [
        'name' => ['type' => 'number', 'label' => 'Over time Rate'],
        'value' => ['type' => 'number', 'label' => 'Double time Rate'],
        'status' => ['type' => 'select', 'label' => 'Status'],
        ]);
        Route::match(['get', 'post'], 'three/{type}', [GenericDataController::class, 'manageData'])
        ->name('data.three')
        ->defaults('fields', [
        'name' =>  ['type' => 'text', 'label' => 'Name'],
        'value' =>  ['type' => 'text', 'label' => 'Value'],
        'status' => ['type' => 'select', 'label' => 'Status'],
        ]);

        Route::match(['get', 'post'], 'two/{type}', [GenericDataController::class, 'manageData'])
        ->name('data.two')
        ->defaults('fields', [
        'name' => ['type' => 'text', 'label' => 'Name'],
        'status' => ['type' => 'select', 'label' => 'Status']
        ]);
        Route::get('/get-states/{country}', [GenericDataController::class, 'getStates']);
        Route::match(['get', 'post'], 'location/info', [GenericDataController::class, 'locationDetail'])->name('data.location');


        Route::match(['get', 'post'], 'four/{type}', [GenericDataController::class, 'manageData'])
        ->name('data.four')
        ->defaults('fields', [
        'name' => ['type' => 'text', 'label' => 'Name'],
        'value' => ['type' => 'text', 'label' => 'Value'],
        'country' =>  ['type' => 'select', 'label' => 'Country'],
        'symbol' =>  ['type' => 'select', 'label' => 'Symbol'],
        'status' =>  ['type' => 'select', 'label' => 'Status'],
        ]);

     

     

        Route::match(['get', 'post'], 'job-group-family-config', [GenericDataController::class, 'jobGroupConfig'])->name('data.job_group_family_config');

        Route::match(['get', 'post'], 'division-branch-zone-config', [GenericDataController::class, 'divisionBranchZoneConfig'])->name('data.division_branch_zone_config');

        Route::resource('admin-users', AdminManagementController::class);
        Route::resource('client-users', ClientManagementController::class);
        Route::resource('vendor-users', VendorManagementController::class);

        Route::match(['get', 'post'], 'setting/info', [GenericDataController::class, 'locationDetail'])->name('data.location');

        Route::get('get-states/{country}', [GenericDataController::class, 'getStates']);
        Route::match(['get', 'post'], 'location/info', [GenericDataController::class, 'locationDetail'])->name('data.location');

        // job routes

        Route::match(['get', 'post'], 'setting/info', [GenericDataController::class, 'settingDetail'])->name('setting.info');
        Route::match(['get', 'post'], 'setting/markup', [GenericDataController::class, 'settingMarkup'])->name('setting.markup');
        // Route to fetch settings based on category
        Route::get('setting/fetch/{categoryId}', [GenericDataController::class, 'fetchSettings'])
        ->name('setting.fetch');

        // Route to update the status of a specific setting
        Route::post('setting/update-status/{settingId}', [GenericDataController::class, 'updateSettingStatus'])
            ->name('setting.update-status');

        // Route to store a new setting (if you want to add new settings as well)
        Route::post('setting/store', [GenericDataController::class, 'storeSetting'])
            ->name('setting.store');
        Route::resource('job/catalog', CatalogController::class);
        Route::resource('career-opportunities', CareerOpportunitiesController::class);
        // ajax method routes
        Route::get('load-market-job-template/{category}/{type}', [CatalogController::class, 'loadMarketJobTemplate']);
        Route::post('load-job-template', [CatalogController::class, 'loadJobTemplate'])->name('load_job_template');
        Route::post('division-load', [CatalogController::class, 'divisionLoad'])->name('division_load');
        Route::post('job-rates', [RatesController::class, 'jobRates'])->name('job_rates');
        
        Route::match(['get', 'post'], 'workflow', [GenericDataController::class, 'workflow'])->name('workflow');
        Route::match(['get', 'post'], 'workflow/edit/{id}', [GenericDataController::class, 'workflowEdit'])->name('workflow.edit');
        Route::match(['get', 'post'], 'workflow/store', [GenericDataController::class, 'workflowStore'])->name('workflow.store');

    });
});
