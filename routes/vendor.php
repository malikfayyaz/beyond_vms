<?php 


use Illuminate\Support\Facades\Route;

Route::prefix('vendor')->group(function () {
    Route::get('/', function(){
    	dd(' i am in vendor view now');
    });
    // Add more vendor-specific routes here
});
