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
        Schema::create('contract_edit_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('remote_contractor')->nullable();
            $table->foreignId('created_by')->constrained('clients')->onDelete('cascade');
            $table->string('created_from')->nullable();
            $table->foreignId('category_id')->constrained('settings')->onDelete('cascade');
            $table->foreignId('job_id')->constrained('career_opportunities')->onDelete('cascade');
            $table->foreignId('contract_id')->constrained('career_opportunities_contract')->onDelete('cascade');
            $table->foreignId('candidate_portal_id')->nullable();
            $table->foreignId('candidate_id')->constrained('consultants')->onDelete('cascade');
            $table->string('bill_rate')->nullable();
            $table->string('pay_rate')->nullable();
            $table->string('contractor_overtimerate')->nullable();
            $table->string('client_overtimerate')->nullable();
            $table->string('contractor_doubletimerate')->nullable();
            $table->string('client_doubletimerate')->nullable();
            $table->string('total_estimated_cost')->nullable();
            $table->string('vendor_bill_rate')->nullable();
            $table->string('vendor_overtime_rate')->nullable();
            $table->string('vendor_doubletime_rate')->nullable();
            $table->string('msp_fees')->nullable();
            $table->string('job_brand')->nullable();
            $table->foreignId('hiring_manager')->constrained('clients')->onDelete('cascade');
            $table->string('vendor_account_manager')->nullable();
            $table->foreignId('location')->constrained('locations')->onDelete('cascade');
            $table->foreignId('timesheet_approval_manager')->constrained('clients')->onDelete('cascade');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->datetime('original_start_date')->nullable();
            $table->datetime('effective_date')->nullable();
            $table->string('gl_code_id')->nullable();
            $table->string('group_number')->nullable();
            $table->string('department_code')->nullable();
            $table->string('acc_unit')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('sub_company')->nullable();
            $table->string('location_identifier')->nullable();
            $table->string('job_type')->nullable();
            $table->string('job_level')->nullable();
            $table->string('job_level_notes')->nullable();
            $table->string('hire_type')->nullable();
            $table->string('bu_number')->nullable();
            $table->string('village')->nullable();
            $table->string('sub_village')->nullable();
            $table->string('budget_manager')->nullable();
            $table->string('worker_pay_type')->nullable();
            $table->string('single_resource_job_approved_budget')->nullable();
            $table->string('location_tax')->nullable();
            $table->string('sales_tax')->nullable();
            $table->string('county_tax')->nullable();
            $table->string('expenses_allowed')->nullable();
            $table->string('resume')->nullable();
            $table->string('offer_digital_doc')->nullable();
            $table->string('offer_second_digital_doc')->nullable();
            $table->string('bcbs_lan_id')->nullable();
            $table->enum('remote_option',['Other', 'Contract Rate', 'Job Level'])->nullable();
            $table->string('po_number')->nullable();
            $table->string('fieldglass_id')->nullable();
            $table->string('workday_position_id')->nullable();
            $table->string('markup')->nullable();
            $table->enum('updated_history_screen',['Other', 'Contract Rate', 'Job Level'])->nullable();
            $table->string('candidate_type')->nullable();
            $table->string('placement_type')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('global_classification')->nullable();
            $table->string('sourcing_type')->nullable();
            $table->string('timesheet_method')->nullable();
            $table->enum('contract_update_type', ['Additional Budget', 'Extension' ,'Rate Change', 'Other' , 'Contract Duration', 'Contract Termination', 'Update Job Level', 'Worker Secondary Assignment', 'Cost Center Rate Change', 'New Cost Center Added']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_edit_histories');
    }
};
