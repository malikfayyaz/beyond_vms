<?php




use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{

    TeamController,
    UserController,
    Vendor\VendorController,
    Admin\AdminController,
    Admin\GenericDataController,
    Admin\PermissionController,
    Admin\RoleController,
    Client\ClientController,
    Auth\AuthController,
    Auth\ForgotPasswordController,
    Auth\ResetPasswordController
};
require base_path('routes/client.php');
require base_path('routes/admin.php');
require base_path('routes/vendor.php');
require base_path('routes/consultant.php');
// Public routes
Route::get('/login', function () {
    return redirect('/');
});

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
Route::get('dashboard', [AuthController::class, 'dashboard']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/select-role', [AuthController::class, 'showRoleSelectionForm'])->name('type.select');
Route::post('selectrolepost', [AuthController::class, 'selectRole'])->name('type.selectpost');

// Forgot Password Request Form (GET request)
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');

// Handle the form submission for sending the reset link (POST request)
Route::post('forgot-password', [ForgotPasswordController::class, 'submitForgotPasswordForm'])->name('password.email');
// reset password code
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'submitResetPasswordForm'])->name('password.update');

// Protected routes requiring role selection
Route::middleware(['ensure_role_is_selected'])->group(function () {

    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Routes for Roles
    Route::resource('roles', RoleController::class);
    Route::post('roles/{user}/assign', [RoleController::class, 'assignRole'])->name('roles.assign');

    // Routes for Permissions
    Route::resource('permissions', PermissionController::class);
    Route::get('roles/{role}/assign-permission', [RoleController::class, 'assignPermissionForm'])->name('roles.assignPermissionForm');
    Route::post('roles/{role}/assign-permission', [RoleController::class, 'assignPermission'])->name('roles.assignPermission');
    // User management routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::post('users/profile', [UserController::class, 'profileUpdate'])->name('users.profileUpdate');
    Route::get('users/{user}/assign-role', [UserController::class, 'assignRoleForm'])->name('users.assignRoleForm');
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');
    Route::post('consultant-id', [VendorController::class, 'consultantDetail'])->name('consultant_detail');
    Route::post('show-vendor-markup', [VendorController::class, 'showVendorMarkup'])->name('show_vendor_markup');

    Route::match(['get', 'post'], 'workflow', [GenericDataController::class, 'workflow'])->name('admin.workflow');
    Route::match(['get', 'post'], 'workflow/edit/{id}', [GenericDataController::class, 'workflowEdit'])->name('admin.workflow.edit');
    Route::match(['get', 'post'], 'workflow/store', [GenericDataController::class, 'workflowStore'])->name('admin.workflow.store');

    Route::match(['post'], 'workflow/jobWorkFlowUpdate', [GenericDataController::class, 'workflowStore'])->name('admin.workflow.jobWorkFlowUpdate');


    // Role-specific dashboards
    Route::middleware(['user_role:admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    });
    Route::middleware(['user_role:vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
    });
    Route::middleware(['user_role:client'])->group(function () {
        Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
        Route::resource('/client/career-opportunities', \App\Http\Controllers\Client\CareerOpportunitiesController::class)
            ->names([
                'index' => 'client.career-opportunities.index',
                'create' => 'client.career-opportunities.create',
                'store' => 'client.career-opportunities.store',
                'show' => 'client.career-opportunities.show',
                'edit' => 'client.career-opportunities.edit',
                'update' => 'client.career-opportunities.update',
                'destroy' => 'client.career-opportunities.destroy'
            ]);
    });


});
