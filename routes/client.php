<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Admin\RatesController,
    Client\SubmissionController,
    Client\CareerOpportunitiesOfferController,
};

Route::middleware(['user_role:client'])->group(function () {
    Route::resource('/client/career-opportunities', \App\Http\Controllers\Client\CareerOpportunitiesController::class);
    Route::post('/client/career-opportunities/{id}/copy', [\App\Http\Controllers\Client\CareerOpportunitiesController::class, 'copy'])
        ->name('client.career-opportunities.copy');
    Route::prefix('client')->name('client.')->group(function () {
        Route::match(['get', 'post'], 'submission/index', [SubmissionController::class, 'index'])->name('submission.index');
        Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');

         // offer
         Route::get('offer/{id}/create', [CareerOpportunitiesOfferController::class, 'create'])->name('offer.create');
         Route::post('offer/store', [CareerOpportunitiesOfferController::class, 'store'])->name('offer.store');
         Route::get('offer/index', [CareerOpportunitiesOfferController::class, 'index'])->name('offer.index');
         Route::get('offer/view/{id}', [CareerOpportunitiesOfferController::class, 'show'])->name('offer.show');
        Route::post('job-rates', [RatesController::class, 'jobRates'])->name('job_rates');
        // WorkOrder
        Route::get('workorder/{id}/create', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'create'])->name('workorder.create');
        Route::post('workorder/store', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'store'])->name('workorder.store');
        Route::get('workorder/index', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'index'])->name('workorder.index');
        Route::get('workorder/{id}', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'show'])->name('workorder.show');
        Route::get('workorder/view/{id}', [\App\Http\Controllers\Client\CareerOpportunitiesWorkOrderController::class, 'show'])->name('workorder.show');

    });
});

?>
