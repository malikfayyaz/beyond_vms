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
        Schema::create('contract_extension_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('career_opportunities_contract')->onDelete('cascade');
            $table->unsignedBigInteger('history_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_type')->nullable();
            $table->unsignedBigInteger('ext_status')->nullable();
            $table->unsignedBigInteger('ext_vendor_approval')->nullable();
            $table->unsignedBigInteger('cronjob_status')->nullable();
            $table->datetime('cronjob_date_time')->nullable();
            $table->string('reason_ext_rejection')->nullable();
            $table->text('notes_ext_rejection')->nullable();
            $table->datetime('approval_rejection_date')->nullable();
            $table->unsignedBigInteger('approval_rejection_by')->nullable();
            $table->unsignedBigInteger('reason_of_extension')->nullable();
            $table->text('note_of_extension')->nullable();
            $table->date('new_contract_start_date')->nullable();
            $table->date('new_contract_end_date')->nullable();
            $table->decimal('pay_rate', 10, 2)->nullable()->default(0);
            $table->decimal('bill_rate', 10, 2)->nullable()->default(0);
            $table->decimal('overtime_payrate', 10, 2)->nullable()->default(0);
            $table->decimal('doubletime_payrate', 10, 2)->nullable()->default(0);
            $table->decimal('overtime_billrate', 10, 2)->nullable()->default(0);
            $table->decimal('doubletime_billrate', 10, 2)->nullable()->default(0);
            $table->decimal('vendor_bill_rate', 10, 2)->nullable()->default(0);
            $table->decimal('vendor_pay_rate', 10, 2)->nullable()->default(0);
            $table->decimal('vendor_overtime_payrate', 10, 2)->nullable()->default(0);
            $table->decimal('vendor_doubletime_payrate', 10, 2)->nullable()->default(0);
            $table->decimal('vendor_overtime_billrate', 10, 2)->nullable()->default(0);
            $table->decimal('vendor_doubletime_billrate', 10, 2)->nullable()->default(0);
            $table->string('bill_request_status')->nullable();
            $table->unsignedBigInteger('bill_req_apprej_by')->nullable();
            $table->string('bill_req_apprej_time')->nullable();
            $table->decimal('new_estimate_cost', 10, 2)->nullable()->default(0);
            $table->decimal('vendor_estimate_cost', 10, 2)->nullable()->default(0);
            $table->unsignedBigInteger('contract_work_flow')->nullable();
            $table->unsignedBigInteger('reason_bill_req_rejection')->nullable();
            $table->text('notes_bill_req_rejection')->nullable();
            $table->datetime('bill_req_rejection_date')->nullable();
            $table->unsignedBigInteger('hr_approver')->nullable();
            $table->string('cost_centers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_extension_requests');
    }
};
