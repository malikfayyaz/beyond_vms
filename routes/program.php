<?php 


use Illuminate\Support\Facades\Route;

Route::prefix('program')->group(function () {
    Route::get('/', function(){
    	dd(' i am in program view now');
    });
    // Add more program-specific routes here
});
