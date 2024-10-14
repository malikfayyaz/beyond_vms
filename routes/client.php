<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Admin\RatesController,
    Client\CareerOpportunitiesSubmissionController,
    Client\CareerOpportunitiesOfferController,
    Client\CareerOpportunitiesInterviewController,
};

Route::middleware(['user_role:client'])->group(function () {
    Route::resource('/client/career-opportunities', \App\Http\Controllers\Client\CareerOpportunitiesController::class);
    Route::post('/client/career-opportunities/{id}/copy', [\App\Http\Controllers\Client\CareerOpportunitiesController::class, 'copy'])
        ->name('client.career-opportunities.copy');

        Route::prefix('client')->name('client.')->group(function () {

        Route::match(['get', 'post'], 'submission/index', [CareerOpportunitiesSubmissionController::class, 'index'])->name('submission.index');
        Route::get('/submission/{id}', [CareerOpportunitiesSubmissionController::class, 'show'])->name('submission.show');

         // offer
         Route::get('offer/{id}/create', [CareerOpportunitiesOfferController::class, 'create'])->name('offer.create');
         Route::post('offer/store', [CareerOpportunitiesOfferController::class, 'store'])->name('offer.store');
            Route::post('offer/offerworkflowAccept', [CareerOpportunitiesOfferController::class, 'offerworkflowAccept'])->name('offer.offerworkflowAccept');
         Route::get('offer/index', [CareerOpportunitiesOfferController::class, 'index'])->name('offer.index');
         Route::get('offer/view/{id}', [CareerOpportunitiesOfferController::class, 'show'])->name('offer.show');
        Route::post('job-rates', [RatesController::class, 'jobRates'])->name('job_rates');
        // WorkOrder
        Route::get('workorder/{id}/create', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'create'])->name('workorder.create');
        Route::post('workorder/store', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'store'])->name('workorder.store');
        Route::get('workorder/index', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'index'])->name('workorder.index');
        Route::get('workorder/{id}', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'show'])->name('workorder.show');
        Route::get('workorder/view/{id}', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'show'])->name('workorder.show');

         //interview
        Route::match(['get', 'post'], 'interview/index', [CareerOpportunitiesInterviewController::class, 'index'])->name('interview.index');
        Route::get('interview/{id}/create', [CareerOpportunitiesInterviewController::class, 'create'])->name('interview.create');
        Route::get('interview/{id}', [CareerOpportunitiesInterviewController::class, 'show'])->name('interview.show');
        Route::post('interview/store', [CareerOpportunitiesInterviewController::class, 'store'])->name('interview.store');
        Route::get('interview/{id}/edit', [CareerOpportunitiesInterviewController::class, 'edit'])->name('interview.edit');
        Route::put('interview/{id}/update', [CareerOpportunitiesInterviewController::class, 'update'])->name('interview.update');
        Route::POST('jobWorkFlowApprove', [\App\Http\Controllers\Client\CareerOpportunitiesController::class, 'jobWorkFlowApprove'])->name('jobWorkFlowApprove');
        Route::POST('jobWorkFlowReject', [\App\Http\Controllers\Client\CareerOpportunitiesController::class, 'jobWorkFlowReject'])->name('jobWorkFlowReject');
            // contract
            Route::resource('contracts', \App\Http\Controllers\Client\CareerOpportunitiesContractController::class);


    });
});

?>
