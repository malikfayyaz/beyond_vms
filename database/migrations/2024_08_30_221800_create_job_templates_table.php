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
        Schema::create('job_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_id')->constrained('users')->onDelete('cascade');
            $table->string('created_from', 25)->nullable();
            $table->enum('is_workflow', ['Yes', 'No']);
            $table->string('job_code', 30)->nullable();
            $table->string('job_title', 255)->nullable();
            $table->foreignId('cat_id')->constrained('settings')->onDelete('cascade');
            $table->text('job_description')->nullable();
            $table->enum('status', ['', 'Active', 'Inactive']);
            // $table->unsignedInteger('currency');
            // $table->foreignId('second_level_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('worker_type_id')->constrained('settings')->onDelete('cascade');
            $table->foreignId('profile_worker_type_id')->constrained('settings')->onDelete('cascade');
            $table->foreignId('job_family_id')->constrained('generic_data')->onDelete('cascade');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_templates');
    }
};
