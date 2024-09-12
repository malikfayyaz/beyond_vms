<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Admin\RoleController,
    Admin\PermissionController,
    Admin\GenericDataController,
    Admin\CatalogController,
    Admin\RatesController,
    Admin\CareerOpportunitiesController,
    TeamController,
    UserController,
    Admin\AdminController,
    Admin\AdminManagementController,
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
    Route::get('roles/{role}/assign-permission', [RoleController::class, 'assignPermissionForm'])->name('roles.assignPermissionForm');
    Route::post('roles/{role}/assign-permission', [RoleController::class, 'assignPermission'])->name('roles.assignPermission');
    // User management routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::post('users/profile', [UserController::class, 'profileUpdate'])->name('users.profileUpdate');
    Route::get('users/{user}/assign-role', [UserController::class, 'assignRoleForm'])->name('users.assignRoleForm');
    Route::post('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');

    // Role-specific dashboards
    Route::middleware(['user_role:admin'])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('dashboard', [AdminController::class, 'index'])->name('dashboard');
            Route::get('/admin/catalog/{id}', [CatalogController::class, 'view'])->name('admin.catalog.view');

    Route::match(['get', 'post'], 'three/{type}', [GenericDataController::class, 'manageData'])
    ->name('data.three')
    ->defaults('fields', [
        'name' => 'text',
        'value' => 'text',
        'status' => 'select',
    ]);

    Route::match(['get', 'post'], 'two/{type}', [GenericDataController::class, 'manageData'])
    ->name('data.two')
    ->defaults('fields', [
        'name' => 'text',
        'status' => 'select',
    ]);
    Route::get('/get-states/{country}', [GenericDataController::class, 'getStates']);
    Route::match(['get', 'post'], 'location/info', [GenericDataController::class, 'locationDetail'])->name('data.location');


            Route::match(['get', 'post'], 'four/{type}', [GenericDataController::class, 'manageData'])
            ->name('data.four')
            ->defaults('fields', [
            'name' => 'text',
            'value' => 'text',
            'country' => 'select',
            'symbol' => 'select',
            'status' => 'select',
            ]);

            Route::match(['get', 'post'], 'three/{type}', [GenericDataController::class, 'manageData'])
            ->name('data.three')
            ->defaults('fields', [
                'name' => 'text',
                'value' => 'text',
                'status' => 'select',
            ]);

            Route::match(['get', 'post'], 'two/{type}', [GenericDataController::class, 'manageData'])
            ->name('data.two')
            ->defaults('fields', [
                'name' => 'text',
                'status' => 'select',
            ]);

            Route::match(['get', 'post'], 'job-group-family-config', [GenericDataController::class, 'jobGroupConfig'])->name('data.job_group_family_config');

            Route::match(['get', 'post'], 'division-branch-zone-config', [GenericDataController::class, 'divisionBranchZoneConfig'])->name('data.division_branch_zone_config');

            Route::resource('admin-users', AdminManagementController::class);

            Route::match(['get', 'post'], 'setting/info', [GenericDataController::class, 'locationDetail'])->name('data.location');

            Route::get('get-states/{country}', [GenericDataController::class, 'getStates']);
            Route::match(['get', 'post'], 'location/info', [GenericDataController::class, 'locationDetail'])->name('data.location');

            // job routes

            Route::match(['get', 'post'], 'setting/info', [GenericDataController::class, 'settingDetail'])->name('setting.info');
            Route::match(['get', 'post'], 'setting/markup', [GenericDataController::class, 'settingMarkup'])->name('setting.markup');
            // Route to fetch settings based on category
            Route::get('setting/fetch/{categoryId}', [GenericDataController::class, 'fetchSettings'])
            ->name('setting.fetch');

            // Route to update the status of a specific setting
            Route::post('setting/update-status/{settingId}', [GenericDataController::class, 'updateSettingStatus'])
                ->name('setting.update-status');

            // Route to store a new setting (if you want to add new settings as well)
            Route::post('setting/store', [GenericDataController::class, 'storeSetting'])
                ->name('setting.store');
            Route::resource('job/catalog', CatalogController::class);
            Route::resource('career-opportunities', CareerOpportunitiesController::class);
            // ajax method routes
            Route::get('load-market-job-template/{category}/{type}', [CatalogController::class, 'loadMarketJobTemplate']);
            Route::post('load-job-template', [CatalogController::class, 'loadJobTemplate'])->name('load_job_template');
            Route::post('division-load', [CatalogController::class, 'divisionLoad'])->name('division_load');
            Route::post('job-rates', [RatesController::class, 'jobRates'])->name('job_rates');

    });





    });

    Route::middleware(['user_role:client'])->group(function () {
        Route::get('/client/dashboard', [ClientController::class, 'index'])->name('client.dashboard');
    });
    Route::middleware(['user_role:vendor'])->group(function () {
        Route::get('/vendor/dashboard', [ClientController::class, 'index'])->name('vendor.dashboard');
    });

});
