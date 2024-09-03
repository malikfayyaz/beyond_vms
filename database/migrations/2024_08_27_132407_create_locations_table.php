<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
                $table->id(); // Auto-increment primary key
                $table->string('name', 255);
                // $table->integer('zone_id')->unsigned();
                $table->string('code', 30)->nullable();
                $table->string('address1', 100)->nullable();
                $table->string('address2', 100)->nullable();
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade'); 
                $table->foreignId('state_id')->constrained('states')->onDelete('cascade'); 
                $table->string('city', 100)->nullable();
                $table->string('zip_code', 8)->nullable();
                $table->string('lat', 30)->nullable();
                $table->string('lon', 30)->nullable();
                $table->enum('status', ['', 'Active', 'Inactive']);
                $table->timestamps(); 
    
                // Define indexes and foreign keys if necessary
                
               
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
