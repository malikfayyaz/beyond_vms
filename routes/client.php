<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Client\SubmissionController,
};

Route::middleware(['user_role:client'])->group(function () {
    Route::prefix('client')->name('client.')->group(function () {
        Route::match(['get', 'post'], 'submission/index', [SubmissionController::class, 'index'])->name('submission.index');
        Route::get('/submission/{id}', [SubmissionController::class, 'show'])->name('submission.show');
    });
});

?>
