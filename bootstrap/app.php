<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\UserBasedAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(EnsureRoleIsSelected::class);
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'ensure_role_is_selected' => \App\Http\Middleware\EnsureRoleIsSelected::class,
            'user_role' => \App\Http\Middleware\UserBasedAccess::class,
        ]);
        
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
