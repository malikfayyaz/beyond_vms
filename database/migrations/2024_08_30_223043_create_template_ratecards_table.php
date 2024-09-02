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
        Schema::create('template_ratecards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('job_templates')->onDelete('cascade');
            // $table->unsignedBigInteger('experience_id');
            $table->foreignId('level_id')->constrained('settings')->onDelete('cascade'); 
            // $table->unsignedBigInteger('level_id')->nullable();
            $table->decimal('bill_rate', 10, 2);
            $table->decimal('min_bill_rate', 10, 2);
            $table->foreignId('currency')->constrained('generic_data')->onDelete('cascade'); 
            // $table->unsignedInteger('currency');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_ratecards');
    }
};
