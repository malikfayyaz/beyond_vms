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
            $table->string('created_by_type')->nullable();
            $table->decimal('bill_rate',10,2)->nullable();
            $table->decimal('pay_rate',10,2)->nullable();
            $table->decimal('client_overtime_payrate',10,2)->nullable();
            $table->decimal('candidate_overtime_payrate',10,2)->nullable();
            $table->decimal('client_doubletime_payrate',10,2)->nullable();
            $table->decimal('candidate_doubletime_payrate',10,2)->nullable();
            $table->decimal('vendor_bill_rate',10,2)->nullable();
            $table->decimal('vendor_overtime_rate',10,2)->nullable();
            $table->decimal('vendor_doubletime_rate',10,2)->nullable();
            $table->string('markup')->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->unsignedBigInteger('status')->nullable();
            $table->unsignedBigInteger('rejected_by_vendor_id')->nullable();
            $table->unsignedBigInteger('rejection_reason')->nullable();
            $table->datetime('app_rejection_time')->nullable();
            $table->text('request_notes')->nullable();
            $table->unsignedBigInteger('history_id')->nullable();
            $table->datetime('effective_date')->nullable();
            $table->decimal('location_tax',10,2)->nullable();
            $table->string('impacted_timesheet_ids')->nullable();
            $table->decimal('total_estimated_cost',10,2)->nullable();
            $table->string('job_level')->nullable();
            $table->decimal('msp_per',10,2)->nullable();
            $table->unsignedBigInteger('cronjob_status')->nullable();
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
