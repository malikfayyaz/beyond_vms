<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerOpportunitiesWorkorderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_opportunities_workorder', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing id column
            $table->foreignId('submission_id')->constrained('career_opportunities_submission')->onDelete('cascade'); 
            $table->foreignId('career_opportunity_id')->constrained('career_opportunities')->onDelete('cascade'); 
            $table->foreignId('candidate_id')->constrained('consultants')->onDelete('cascade'); 
            $table->foreignId('offer_id')->constrained('career_opportunities_offer')->onDelete('cascade'); 
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');  
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('hiring_manager_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('approval_manager')->constrained('clients')->onDelete('cascade');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('created_by_type')->nullable();
            $table->unsignedBigInteger('modified_by_id')->nullable();
            $table->unsignedBigInteger('modified_by_type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->decimal('wo_pay_rate', 10, 2)->default(0.00);
            $table->decimal('wo_bill_rate', 10, 2)->default(0.00);
            $table->decimal('wo_over_time', 10, 2)->default(0.00);
            $table->decimal('wo_double_time', 10, 2)->default(0.00);
            $table->decimal('wo_client_over_time', 10, 2)->default(0.00);
            $table->decimal('wo_client_double_time', 10, 2)->default(0.00);
            $table->decimal('vendor_bill_rate', 10, 2)->default(0.00);
            $table->decimal('vendor_overtime_rate', 10, 2)->default(0.00);
            $table->decimal('vendor_doubletime_rate', 10, 2)->default(0.00);
            $table->decimal('estimate_cost', 10, 2)->default(0.00);
            $table->decimal('single_resource_job_approved_budget', 10, 2)->default(0.00);
            $table->decimal('job_other_amount', 10, 2)->default(0.00);
            $table->decimal('markup', 10, 2)->default(0.00);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->dateTime('accept_date')->nullable();
            $table->dateTime('wo_release_date')->nullable();
            $table->foreignId('job_level')->constrained('settings')->onDelete('cascade');
            $table->foreignId('division_id')->constrained('generic_data')->onDelete('cascade');
            $table->enum('remote_option', ['Yes', 'No'])->default('No');
            $table->enum('expenses_allowed', ['Yes', 'No'])->default('No');
            $table->string('job_type', 255)->nullable();
            $table->unsignedBigInteger('timesheet_method')->nullable();
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_opportunities_workorder');
    }
}
