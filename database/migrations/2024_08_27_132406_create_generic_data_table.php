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
        Schema::create('generic_data', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->string('symbol_id');
            
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade'); 
            
            $table->enum('status', ['active', 'inactive']);
            $table->string('type'); // This will distinguish different types like 'account_code', 'business_unit', etc.
            $table->timestamps(); // created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generic_data');
    }
};
