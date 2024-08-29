<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Admin\RoleController,
    Admin\PermissionController,
    Admin\GenericDataController,
    Admin\JobController,
    Admin\CatalogController,
    TeamController,
    UserController,
    Admin\AdminController,
    Client\ClientController,
    Auth\AuthController,
    Auth\ForgotPasswordController,
    Auth\ResetPasswordController
};

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

    // User management routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}/assign-role', [UserController::class, 'assignRoleForm'])->name('users.assignRoleForm');
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');

    // Role-specific dashboards
    Route::middleware(['user_role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::match(['get', 'post'], '/admin/three/{type}', [GenericDataController::class, 'manageData'])
    ->name('admin.data.three')
    ->defaults('fields', [
        'name' => 'text',
        'value' => 'text',
        'status' => 'select',
    ]);
   
    Route::match(['get', 'post'], '/admin/two/{type}', [GenericDataController::class, 'manageData'])
    ->name('admin.data.two')
    ->defaults('fields', [
        'name' => 'text',
        'status' => 'select',
    ]);
    Route::get('/get-states/{country}', [GenericDataController::class, 'getStates']);
    Route::match(['get', 'post'], '/admin/location/info', [GenericDataController::class, 'locationDetail'])->name('admin.data.location');

    // job routes
    Route::get('/admin/job/create', [JobController::class, 'create'])->name('jobs.create');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('job/catalog', CatalogController::class);
    });

  

    
    
    });

    Route::middleware(['user_role:client'])->group(function () {
        Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
    });
    
});
