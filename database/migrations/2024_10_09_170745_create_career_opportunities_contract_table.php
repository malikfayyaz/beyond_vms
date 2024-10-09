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
        Schema::create('career_opportunities_contract', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workorder_id')->constrained('career_opportunities_workorder')->onDelete('cascade');
            $table->foreignId('offer_id')->constrained('career_opportunities_offer')->onDelete('cascade'); 
            $table->foreignId('submission_id')->constrained('career_opportunities_submission')->onDelete('cascade'); 
            $table->foreignId('career_opportunity_id')->constrained('career_opportunities')->onDelete('cascade'); 
            $table->foreignId('hiring_manager_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('consultants')->onDelete('cascade'); 
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');  
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('created_by_type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('effective_date')->nullable();
            $table->string('is_billable_resource', 255)->nullable();
            $table->unsignedBigInteger('contract_type')->nullable();
            $table->decimal('total_estimated_cost', 10, 2)->default(0.00);
            $table->decimal('job_other_amount', 10, 2)->default(0.00);
            $table->tinyInteger('termination_status')->nullable();
            $table->unsignedBigInteger('termination_reason')->nullable();
            $table->text('termination_notes')->nullable();
            $table->unsignedBigInteger('term_by_id')->nullable();
            $table->unsignedBigInteger('term_by_type')->nullable();
            $table->date('termination_date')->nullable();
            $table->enum('future_hire', ['Yes', 'No'])->default('No');
            $table->unsignedBigInteger('type_of_timesheet')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_opportunities_contract');
    }
};
