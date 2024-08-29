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
        Schema::create('states', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade'); // Foreign key to countries table
            $table->string('name'); // State name
            $table->string('abbreviation', 10)->nullable(); // Optional abbreviation (e.g., "CA" for California)
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
