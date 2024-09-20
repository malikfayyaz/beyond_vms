<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Vendor\VendorController,
    Vendor\SubmissionController,
};
Route::middleware(['user_role:vendor'])->group(function () {
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
        Route::resource('career-opportunities', \App\Http\Controllers\Vendor\CareerOpportunitiesController::class);
        Route::get('submission/{id}/create', [SubmissionController::class, 'create'])->name('submission.create');
        Route::post('submission/store', [SubmissionController::class, 'store'])->name('submission.store');
        Route::match(['get', 'post'], 'submission/index', [SubmissionController::class, 'index'])->name('submission.index');
        Route::get('/submission-list', [SubmissionController::class, 'getSubmissions'])->name('submission.list');
        Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');
    });

});
