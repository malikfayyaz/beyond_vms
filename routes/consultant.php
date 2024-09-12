<?php 


use Illuminate\Support\Facades\Route;

Route::prefix('consultant')->group(function () {
    Route::get('/',function(){
    	dd(' i am in consultant view now');
    });
});
