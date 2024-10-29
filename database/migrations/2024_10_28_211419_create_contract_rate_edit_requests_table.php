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
        Schema::create('contract_rate_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('career_opportunities_contract')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('created_by_type');
            $table->decimal('bill_rate',10,2);
            $table->decimal('pay_rate',10,2);
            $table->decimal('client_overtime_payrate',10,2);
            $table->decimal('candidate_overtime_payrate',10,2);
            $table->decimal('client_doubletime_payrate',10,2);
            $table->decimal('candidate_doubletime_payrate',10,2);
            $table->decimal('vendor_bill_rate',10,2);
            $table->decimal('vendor_overtime_rate',10,2);
            $table->decimal('vendor_doubletime_rate',10,2);
            $table->string('markup');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->unsignedBigInteger('status');
            $table->unsignedBigInteger('rejected_by_vendor_id');
            $table->unsignedBigInteger('rejection_reason');
            $table->datetime('app_rejection_time');
            $table->text('request_notes');
            $table->unsignedBigInteger('history_id');
            $table->datetime('effective_date');
            $table->decimal('location_tax',10,2);
            $table->string('impacted_timesheet_ids');
            $table->decimal('total_estimated_cost',10,2);
            $table->string('job_level');
            $table->decimal('msp_per',10,2);
            $table->unsignedBigInteger('cronjob_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_rate_edit_requests');
    }
};
