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
        Schema::create('vendor_job_releases', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('created_by');
            $table->string('created_by_type')->nullable();
            $table->integer('group_id')->nullable();
            $table->string('group_name')->nullable();
            $table->foreignId('job_id')->references('id')->on('career_opportunities')->onDelete('cascade');
            $table->integer('history_id')->nullable();
            $table->integer('status')->nullable();
            $table->datetime('job_released_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_job_releases');
    }
};
