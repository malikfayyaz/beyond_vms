<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Vendor\VendorController,
    Vendor\SubmissionController,
    Vendor\CareerOpportunitiesOfferController,
};
Route::middleware(['user_role:vendor'])->group(function () {
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
        Route::resource('career-opportunities', \App\Http\Controllers\Vendor\CareerOpportunitiesController::class);
        Route::get('submission/{id}/create', [SubmissionController::class, 'create'])->name('submission.create');
        Route::post('submission/store', [SubmissionController::class, 'store'])->name('submission.store');
        Route::match(['get', 'post'], 'submission/index', [SubmissionController::class, 'index'])->name('submission.index');
        Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');
        // offer
        Route::get('offer/{id}/create', [CareerOpportunitiesOfferController::class, 'create'])->name('offer.create');
        Route::post('offer/store', [CareerOpportunitiesOfferController::class, 'store'])->name('offer.store');
        Route::get('offer/index', [CareerOpportunitiesOfferController::class, 'index'])->name('offer.index');
        Route::post('offer/accept-offer', [CareerOpportunitiesOfferController::class, 'acceptOffer'])->name('offer.accept');
        Route::get('offer/view/{id}', [CareerOpportunitiesOfferController::class, 'show'])->name('offer.show');
        Route::get('workorder/index', [\App\Http\Controllers\Vendor\CareerOpportunitiesWorkOrderController::class, 'index'])->name('workorder.index');
        Route::get('workorder/{id}', [\App\Http\Controllers\Vendor\CareerOpportunitiesWorkOrderController::class, 'show'])->name('workorder.show');

    });

});
