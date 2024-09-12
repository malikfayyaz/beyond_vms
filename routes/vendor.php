<?php 


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Vendor\VendorController,
    Vendor\SubmissionController,
};
Route::middleware(['user_role:vendor'])->group(function () {
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
        Route::resource('submission', SubmissionController::class);
    });

});
