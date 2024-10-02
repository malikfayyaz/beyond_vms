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
        Schema::create('career_opportunities_interviews', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('submission_id')->constrained('career_opportunities_submission')->onDelete('cascade'); 

             // Consultant and Job details
            $table->foreignId('candidate_id')->constrained('consultants')->onDelete('cascade'); 

            $table->foreignId('career_opportunity_id')->constrained('career_opportunities')->onDelete('cascade'); 


            $table->string('event_name')->nullable();    // Event Name
            $table->foreignId('interview_duration')->constrained('settings')->onDelete('cascade'); // Interview Duration   
            $table->foreignId('time_zone')->constrained('settings')->onDelete('cascade');// Time Zone
            $table->foreignId('interview_type')->constrained('settings')->onDelete('cascade');
             
            // Date Information
            $table->date('recommended_date')->nullable(); // Main interview date
            $table->date('other_date_1')->nullable();    // Alternate Date 1
            $table->date('other_date_2')->nullable();    // Alternate Date 2
            $table->date('other_date_3')->nullable();    // Alternate Date 3
             
            // Location
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
             
            // Job Attachment
            $table->string('job_attachment')->nullable();  // File path for job attachment
            
            // Interview Instructions
            $table->text('interview_instructions')->nullable(); // Additional instructions
             
            // Interview Members
            $table->json('interview_members')->nullable();  // List of interview members
            
            $table->timestamps();
             
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_opportunities_interviews');
    }
};
