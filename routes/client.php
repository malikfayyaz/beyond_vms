<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Admin\RatesController,
    Client\SubmissionController,
    Client\CareerOpportunitiesOfferController,
};

Route::middleware(['user_role:client'])->group(function () {
    Route::prefix('client')->name('client.')->group(function () {
        Route::match(['get', 'post'], 'submission/index', [SubmissionController::class, 'index'])->name('submission.index');
        Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');

         // offer
         Route::get('offer/{id}/create', [CareerOpportunitiesOfferController::class, 'create'])->name('offer.create');
         Route::post('offer/store', [CareerOpportunitiesOfferController::class, 'store'])->name('offer.store');
         Route::get('offer/index', [CareerOpportunitiesOfferController::class, 'index'])->name('offer.index');
         Route::get('offer/view/{id}', [CareerOpportunitiesOfferController::class, 'show'])->name('offer.show');
        Route::post('job-rates', [RatesController::class, 'jobRates'])->name('job_rates');

    });
});

?>
