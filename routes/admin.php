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
    Admin\VendorManagementController,
    Admin\CareerOpportunitiesOfferController,
    Admin\CareerOpportunitiesSubmissionController,
    Admin\CareerOpportunitiesInterviewController,
    Admin\CareerOpportunitiesContractController,
};
Route::middleware(['user_role:admin'])->group(function () {
    Route::resource('/admin/career-opportunities', CareerOpportunitiesController::class);

// Define the custom copy route
    Route::post('/admin/career-opportunities/{id}/copy', [CareerOpportunitiesController::class, 'copy'])
        ->name('admin.career-opportunities.copy');
    // In your routes file (web.php)
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
        Route::POST('career-opportunities/saveNotes', [CareerOpportunitiesController::class, 'saveNotes'])->name('saveNotes');

        
        // ajax method routes
        
        
        Route::post('job-rates', [RatesController::class, 'jobRates'])->name('job_rates');



        Route::match(['get', 'post'], 'job-workflow-data', [CareerOpportunitiesController::class, 'jobWorkFlowData'])->name('jobWorkFlowData');



        // offer
        Route::get('offer/{id}/create', [CareerOpportunitiesOfferController::class, 'create'])->name('offer.create');
        Route::post('offer/store', [CareerOpportunitiesOfferController::class, 'store'])->name('offer.store');
        Route::post('offer/offerworkflowAccept', [CareerOpportunitiesOfferController::class, 'offerworkflowAccept'])->name('offer.offerworkflowAccept');
        Route::get('offer/index', [CareerOpportunitiesOfferController::class, 'index'])->name('offer.index');
        Route::get('offer/{id}', [CareerOpportunitiesOfferController::class, 'show'])->name('offer.show');
        Route::get('offer/view/{id}', [CareerOpportunitiesOfferController::class, 'show'])->name('offer.show');
        // WorkOrder
        Route::get('workorder/{id}/create', [\App\Http\Controllers\Admin\CareerOpportunitiesWorkOrderController::class, 'create'])->name('workorder.create');
        Route::post('workorder/store', [\App\Http\Controllers\Admin\CareerOpportunitiesWorkOrderController::class, 'store'])->name('workorder.store');
        Route::get('workorder/index', [\App\Http\Controllers\Admin\CareerOpportunitiesWorkOrderController::class, 'index'])->name('workorder.index');
        Route::get('workorder/view/{id}', [\App\Http\Controllers\Admin\CareerOpportunitiesWorkOrderController::class, 'show'])->name('workorder.show');
        Route::post('workorder/withdrawWorkorder', [\App\Http\Controllers\Admin\CareerOpportunitiesWorkOrderController::class, 'withdrawWorkorder'])->name('workorder.withdrawWorkorder');       
        //workflow

       Route::match(['get', 'post'], 'workflow', [GenericDataController::class, 'workflow'])->name('workflow');
       Route::match(['get', 'post'], 'workflow/create/{id}', [GenericDataController::class, 'workflowCreate'])->name('workflow.create');
       Route::match(['get', 'post'], 'workflow/store', [GenericDataController::class, 'workflowStore'])->name('workflow.store');
       Route::match(['get', 'post'], 'workflow/edit/{id}', [GenericDataController::class, 'workflowEdit'])->name('workflow.edit');
       Route::put('workflow/{id}/update', [GenericDataController::class, 'workflowUpdate'])->name('workflow.update');

       //submission



        Route::match(['get', 'post'], 'submission/index', [CareerOpportunitiesSubmissionController::class, 'index'])->name('submission.index');
        Route::get('submission/{id}', [CareerOpportunitiesSubmissionController::class, 'show'])->name('submission.show');
        Route::post('reject-candidate', [CareerOpportunitiesSubmissionController::class, 'rejectCandidate'])->name('interview.reject_candidate');
        Route::post('shortlist-candidate', [CareerOpportunitiesSubmissionController::class, 'shortlistCandidate'])->name('interview.shortlist_candidate');
        //interview
        Route::match(['get', 'post'], 'interview/index', [CareerOpportunitiesInterviewController::class, 'index'])->name('interview.index');
        Route::get('interview/{id}/create', [CareerOpportunitiesInterviewController::class, 'create'])->name('interview.create');
        Route::get('interview/{id}', [CareerOpportunitiesInterviewController::class, 'show'])->name('interview.show');
        Route::post('interview/store', [CareerOpportunitiesInterviewController::class, 'store'])->name('interview.store');
        Route::get('interview/{id}/edit', [CareerOpportunitiesInterviewController::class, 'edit'])->name('interview.edit');
        Route::put('interview/{id}/update', [CareerOpportunitiesInterviewController::class, 'update'])->name('interview.update');
        

       Route::POST('career-opportunities/{id}/jobApprove', [CareerOpportunitiesController::class, 'jobApprove'])->name('jobApprove');
       Route::POST('career-opportunities/{id}/jobReject', [CareerOpportunitiesController::class, 'jobReject'])->name('jobReject');
       Route::POST('/releaseJobVendor', [CareerOpportunitiesController::class, 'releaseJobVendor'])->name('releaseJobVendor');


        Route::POST('jobWorkFlowApprove', [CareerOpportunitiesController::class, 'jobWorkFlowApprove'])->name('jobWorkFlowApprove');
        Route::POST('jobWorkFlowReject', [CareerOpportunitiesController::class, 'jobWorkFlowReject'])->name('jobWorkFlowReject');

        Route::POST('rejectAdminJob', [CareerOpportunitiesController::class, 'rejectAdminJob'])->name('rejectAdminJob');

        // contract
        Route::resource('contracts', CareerOpportunitiesContractController::class);
        Route::POST('contracts/save-comments', [CareerOpportunitiesContractController::class, 'saveComments'])->name('saveComments');

        Route::get('/career-opportunities/{id}/vendorrelease', [CareerOpportunitiesController::class, 'vendorrelease'])->name('admin.career-opportunities.vendorrelease');

        Route::get('/career-opportunities/{id}/submission', [CareerOpportunitiesController::class, 'jobSubmission'])->name('jobSubmission');

        Route::get('/career-opportunities/{id}/todayinterview', [CareerOpportunitiesController::class, 'jobTodayInterview'])->name('jobTodayInterview');
        Route::get('/career-opportunities/{id}/otherinterview', [CareerOpportunitiesController::class, 'jobOtherInterview'])->name('jobOtherInterview');

        Route::get('/career-opportunities/{id}/jobOffer', [CareerOpportunitiesController::class, 'jobOffer'])->name('jobOffer');

        Route::get('/career-opportunities/{id}/jobWorkorder', [CareerOpportunitiesController::class, 'jobWorkorder'])->name('jobWorkorder');

        Route::get('/career-opportunities/{id}/jobRanking', [CareerOpportunitiesController::class, 'jobRanking'])->name('jobRanking');

        Route::match(['get', 'post'], '/career-opportunities/{id}/jobteammember', [CareerOpportunitiesController::class, 'jobteammember'])->name('jobteammember');
        Route::match(['get', 'post'], '/career-opportunities/{id}/pmoteammember', [CareerOpportunitiesController::class, 'pmoteammember'])->name('pmoteammember');


        Route::POST('contracts/contractBudgetWorkflow', [CareerOpportunitiesContractController::class, 'contractBudgetWorkflow'])->name('contract.contractBudgetWorkflow');
        


    });
});
