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
        Schema::create('career_opportunities', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->integer('coupa_id')->nullable();
            $table->string('title')->nullable();
            $table->string('alternative_job_title')->nullable();
            $table->foreignId('cat_id')->constrained('settings')->onDelete('cascade');
            $table->foreignId('user_type')->constrained('settings')->onDelete('cascade');
            $table->string('organization_name', 255)->nullable();
            $table->foreignId('template_id')->constrained('job_templates')->onDelete('cascade');
            $table->foreignId('job_level')->constrained('settings')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_subclient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('division_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('region_zone_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('gl_code_id')->constrained('generic_data')->onDelete('cascade');
            $table->boolean('contract_charges')->default(0);
            $table->foreignId('hiring_manager')->constrained('users')->onDelete('cascade');
            $table->text('skills')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('client_notes')->nullable();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('address', 250)->nullable();
            $table->integer('num_openings')->nullable()->default(0);
            $table->string('type', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('attachment')->nullable();
            $table->text('add_info')->nullable();
            $table->decimal('min_bill_rate', 10, 2)->nullable()->default(0);
            $table->decimal('max_bill_rate', 10, 2)->nullable()->default(0);
            $table->decimal('hour_day_week_billrate', 10, 2)->nullable()->default(0);
            $table->string('payment_type', 111)->nullable();
            $table->text('invite_team_member')->nullable();
            $table->string('start_date', 55)->nullable();
            $table->string('end_date', 25)->nullable();
            $table->string('shift', 111)->nullable();
            $table->string('hours_per_week', 11)->nullable();
            $table->integer('hours_per_day')->nullable()->default(0);
            $table->integer('day_per_week')->nullable()->default(0);
            $table->string('background_verification', 11)->nullable();
            $table->string('type_of_job', 25)->nullable();
            $table->string('pre_candidate', 11)->nullable();
            $table->decimal('pre_max_rate', 10, 2)->nullable()->default(0);
            $table->string('pre_name', 111)->nullable();
            $table->string('pre_middle_name')->nullable();
            $table->string('pre_last_name')->nullable();
            $table->string('candidate_phone', 30)->nullable();
            $table->string('candidate_email')->nullable();
            $table->integer('timesheet_approver')->nullable()->default(0);
            $table->string('pre_supplier_name', 255)->nullable();
            $table->string('pre_current_rate', 25)->nullable();
            $table->string('pre_payment_type', 25)->nullable();
            $table->string('pre_reference_code')->nullable();
            $table->decimal('regular_hours', 10, 1)->nullable()->default(0);
            $table->decimal('regular_hours_cost', 10, 2)->nullable()->default(0);
            $table->integer('overtime_hours')->nullable()->default(0);
            $table->decimal('overtime_hours_cost', 10, 2)->nullable()->default(0);
            $table->decimal('expense_cost', 10, 2)->nullable()->default(0);
            $table->decimal('single_resource_total_cost', 10, 2)->nullable()->default(0);
            $table->decimal('all_resources_total_cost', 15, 2)->nullable()->default(0);
            $table->string('pre_total_estimate_code')->nullable();
            $table->string('work_flow', 111)->nullable();
            $table->string('jobStatus', 111)->nullable();
            $table->enum('interview_process', ['No', 'Yes'])->default('Yes');
            $table->string('closed_reason', 255)->nullable();
            $table->text('closed_notes')->nullable();
            $table->dateTime('closed_hold_date')->nullable();
            $table->integer('closed_hold_by')->nullable()->default(0);
            $table->dateTime('release_date')->nullable();
            $table->integer('jobstep2_complete')->nullable()->default(0);
            $table->foreignId('labour_type')->constrained('settings')->onDelete('cascade');
            $table->foreignId('worker_type_id')->constrained('settings')->onDelete('cascade');
            $table->foreignId('hire_reason_id')->constrained('settings')->onDelete('cascade');
            $table->enum('remote_option', ['Yes', 'No'])->default('No');
            $table->enum('expenses_allowed', ['Yes', 'No'])->default('No');
            $table->enum('retiree', ['Yes', 'No'])->default('No');
            $table->enum('travel_required', ['Yes', 'No'])->default('No');
            $table->string('ledger_type_id')->nullable();
            $table->integer('job_code')->nullable();
            $table->string('ledger_code')->nullable();
            $table->string('client_name', 255)->nullable();
            $table->integer('rejected_by')->nullable()->default(0);
            $table->string('rejected_type', 111)->nullable();
            $table->integer('reason_for_rejection')->nullable()->default(0);
            $table->text('note_for_rejection')->nullable();
            $table->dateTime('date_rejected')->nullable();
            $table->date('hire_by_date')->nullable();
            $table->enum('job_approval', ['Yes', 'No'])->default('No');
            $table->enum('resume_required', ['Yes', 'No'])->default('No');
            $table->enum('client_billable', ['Yes', 'No'])->default('No');
            $table->enum('background_check_required', ['Yes', 'No'])->default('No');
            $table->enum('security_clearance', ['', 'Yes', 'No'])->default('');
            $table->integer('create_by')->nullable()->default(0);
            $table->integer('update_by')->nullable()->default(0);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_opportunities');
    }
};
