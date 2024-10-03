<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Vendor\VendorController,
    Vendor\CareerOpportunitiesSubmissionController,
    Vendor\CareerOpportunitiesOfferController,
    Vendor\CareerOpportunitiesInterviewController,
    Vendor\CareerOpportunitiesWorkOrderController,
};
Route::middleware(['user_role:vendor'])->group(function () {
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
        Route::resource('career-opportunities', \App\Http\Controllers\Vendor\CareerOpportunitiesController::class);
        
        //submission
        Route::get('submission/{id}/create', [CareerOpportunitiesSubmissionController::class, 'create'])->name('submission.create');
        Route::post('submission/store', [CareerOpportunitiesSubmissionController::class, 'store'])->name('submission.store');
        Route::match(['get', 'post'], 'submission/index', [CareerOpportunitiesSubmissionController::class, 'index'])->name('submission.index');
        Route::get('/submission/{id}', [CareerOpportunitiesSubmissionController::class, 'show'])->name('submission.show');
        // offer
        Route::get('offer/{id}/create', [CareerOpportunitiesOfferController::class, 'create'])->name('offer.create');
        Route::post('offer/store', [CareerOpportunitiesOfferController::class, 'store'])->name('offer.store');
        Route::get('offer/index', [CareerOpportunitiesOfferController::class, 'index'])->name('offer.index');
        Route::post('offer/accept-offer', [CareerOpportunitiesOfferController::class, 'acceptOffer'])->name('offer.accept');
        Route::get('offer/view/{id}', [CareerOpportunitiesOfferController::class, 'show'])->name('offer.show');
        // workorder
        Route::get('workorder/index', [CareerOpportunitiesWorkOrderController::class, 'index'])->name('workorder.index');
        Route::get('workorder/{id}', [CareerOpportunitiesWorkOrderController::class, 'show'])->name('workorder.show');
        Route::post('workorder/store', [CareerOpportunitiesWorkOrderController::class, 'store'])->name('workorder.store');

         //interview
         Route::match(['get', 'post'], 'interview/index', [CareerOpportunitiesInterviewController::class, 'index'])->name('interview.index');
         Route::get('interview/{id}/create', [CareerOpportunitiesInterviewController::class, 'create'])->name('interview.create');
         Route::post('interview/store', [CareerOpportunitiesInterviewController::class, 'store'])->name('interview.store');
         Route::get('interview/{id}/edit', [CareerOpportunitiesInterviewController::class, 'edit'])->name('interview.edit');
         Route::put('interview/{id}/update', [CareerOpportunitiesInterviewController::class, 'update'])->name('interview.update');
 
    });

});
