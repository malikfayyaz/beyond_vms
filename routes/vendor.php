<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Vendor\VendorController,
    Vendor\CareerOpportunitiesController,
    Vendor\CareerOpportunitiesSubmissionController,
    Vendor\CareerOpportunitiesOfferController,
    Vendor\CareerOpportunitiesInterviewController,
    Vendor\CareerOpportunitiesWorkOrderController,
    Vendor\TimesheetController,
    Vendor\StaffMemberController,
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
        Route::post('submission/withdrawSubmission', [CareerOpportunitiesSubmissionController::class, 'withdrawSubmission'])->name('submission.withdraw');
        Route::delete('submission/{id}', [CareerOpportunitiesSubmissionController::class, 'destroy'])->name('submission.destroy');
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
        Route::delete('workorderbackground/{id}', [CareerOpportunitiesWorkOrderController::class, 'destroy'])->name('workorderbackground.destroy');

        //interview
        Route::match(['get', 'post'], 'interview/index', [CareerOpportunitiesInterviewController::class, 'index'])->name('interview.index');
        Route::get('interview/{id}', [CareerOpportunitiesInterviewController::class, 'show'])->name('interview.show');
        Route::post('interview/{id}/saveTiming', [CareerOpportunitiesInterviewController::class, 'saveInterviewTiming'])->name('interview.saveTiming');

        // contract
        Route::resource('contracts', \App\Http\Controllers\Vendor\CareerOpportunitiesContractController::class);

        // timesheet
        Route::get('timesheet/select-candidate', [TimesheetController::class, 'selectCandidate'])->name('timesheet.select_candidate');
        Route::post('timesheet/step-one', [TimesheetController::class, 'stepOne'])->name('timesheet.step_one');
        Route::get('timesheet/{contract_id}/step-one', [TimesheetController::class, 'stepOneView'])->name('timesheet.step_one_view');
        Route::post('timesheet/step-one-store', [TimesheetController::class, 'stepOneStore'])->name('timesheet.step_one_store');
        Route::get('timesheet/step2', [TimesheetController::class, 'step2'])->name('timesheet.timesheetStep2');
        Route::post('timesheet/step-two-store', [TimesheetController::class, 'stepTwoStore'])->name('timesheet.step_two_store');

        Route::get('/career-opportunities/{id}/submission', [CareerOpportunitiesController::class, 'jobSubmission'])->name('jobSubmission');

        Route::get('/career-opportunities/{id}/todayinterview', [CareerOpportunitiesController::class, 'jobTodayInterview'])->name('jobTodayInterview');
        Route::get('/career-opportunities/{id}/otherinterview', [CareerOpportunitiesController::class, 'jobOtherInterview'])->name('jobOtherInterview');
        Route::get('/career-opportunities/{id}/jobOffer', [CareerOpportunitiesController::class, 'jobOffer'])->name('jobOffer');
        Route::get('/career-opportunities/{id}/jobWorkorder', [CareerOpportunitiesController::class, 'jobWorkorder'])->name('jobWorkorder');
//staff member
        Route::get('staffmember/index', [\App\Http\Controllers\Vendor\StaffMemberController::class, 'index'])->name('staffmember.index');
        Route::get('staffmember/create', [\App\Http\Controllers\Vendor\StaffMemberController::class, 'create'])->name('staffmember.create');
        Route::put('staffmember//{id}/update', [\App\Http\Controllers\Vendor\StaffMemberController::class, 'update'])->name('staffmember.update');
        Route::get('staffmember/{id}/edit', [\App\Http\Controllers\Vendor\StaffMemberController::class, 'edit'])->name('staffmember.edit');
        Route::post('staffmember/store', [\App\Http\Controllers\Vendor\StaffMemberController::class, 'store'])->name('staffmember.store');
        Route::delete('staffmember/{id}', [StaffMemberController::class, 'destroy'])->name('staffmember.destroy');
    });

});
