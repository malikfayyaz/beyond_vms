<?php


use Illuminate\Support\Facades\Route;
 Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::prefix('consultant')->group(function () {
    Route::get('/',function(){
    	dd(' i am in consultant view now');
    });
});
