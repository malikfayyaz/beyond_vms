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
        Schema::create('job_family_group_config', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('job_family_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('job_family_group_id')->constrained('generic_data')->onDelete('cascade');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_family_group_config');
    }
};
