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
        Schema::create('career_opportunities_interview_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interview_id')->constrained('career_opportunities_interviews')->onDelete('cascade');  
            $table->date('schedule_date');              
            $table->time('start_time');                 
            $table->time('end_time');                   
            $table->integer('schedule_date_order');      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_opportunities_interview_dates');
    }
};
