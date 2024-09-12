<?php 

use Illuminate\Support\Facades\Route;

Route::prefix('client')->group(function () {
    Route::get('/', function(){
    	dd(' i am in client view now');
    });
    // Add more client-specific routes here
});

?>
