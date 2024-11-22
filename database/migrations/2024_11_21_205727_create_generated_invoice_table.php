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
        Schema::create('generated_invoice', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('client_id')->nullable();
            $table->string('client_month', 30)->nullable();
            $table->date('duration_start_date')->nullable();
            $table->date('duration_end_date')->nullable();
            $table->string('duration_cycle', 10)->nullable();
            $table->bigInteger('client_location')->nullable();
            $table->bigInteger('timesheet_id')->nullable();
            $table->bigInteger('job_id')->nullable();
            $table->integer('contract_id')->nullable();
            $table->bigInteger('candidate_id')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->enum('invoice_type', ['General', 'SOW Time and Material', ''])->nullable();
            $table->string('generated_by_name', 100)->nullable();
            $table->integer('generated_by_field')->nullable();
            $table->text('config_id')->nullable();
            $table->integer('currency')->nullable();
            $table->string('charge_number', 100)->nullable();
            $table->date('invoice_start_date')->nullable();
            $table->date('invoice_end_date')->nullable();
            $table->decimal('total_regular_hours', 10, 2)->nullable();
            $table->decimal('total_overtime_hours', 10, 2)->nullable();
            $table->decimal('total_doubletime_hours', 10, 2)->nullable();
            $table->decimal('total_hours', 10, 2)->nullable();
            $table->decimal('total_billrate', 10, 2)->nullable();
            $table->decimal('total_billrate_with_tax', 10, 2)->nullable();
            $table->decimal('tax_per', 10, 2)->nullable();
            $table->integer('status')->nullable();
            $table->tinyInteger('reverse_status')->nullable();
            $table->string('voucher_batch_number', 100)->nullable();
            $table->string('voucher_document_number', 100)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_id', 100)->nullable();
            $table->string('payment_number', 100)->nullable();
            $table->decimal('payment_amount', 10, 2)->nullable();
            $table->string('paid_currency', 100)->nullable();
            $table->date('void_date')->nullable();
            $table->string('void_payment_number', 100)->nullable();
            $table->string('void_payment_id', 100)->nullable();
            $table->string('payment_method_code', 100)->nullable();
            $table->string('payment_method_name', 100)->nullable();
            $table->string('reverse_voucher_batch_number', 100)->nullable();
            $table->string('reverse_voucher_document_number', 100)->nullable();
            $table->date('reverse_payment_date')->nullable();
            $table->string('reverse_payment_id', 100)->nullable();
            $table->string('reverse_payment_number', 100)->nullable();
            $table->decimal('reverse_payment_amount', 10, 2)->nullable();
            $table->string('reverse_paid_currency', 100)->nullable();
            $table->date('reverse_void_date')->nullable();
            $table->string('reverse_void_payment_number', 100)->nullable();
            $table->string('reverse_void_payment_id', 100)->nullable();
            $table->string('reverse_payment_method_code', 100)->nullable();
            $table->string('reverse_payment_method_name', 100)->nullable();
            $table->tinyInteger('consolidate_invoice_generated')->nullable();
            $table->tinyInteger('consolidated_reversed')->nullable();
            $table->integer('not_deduct_budget')->nullable();
            $table->dateTime('date_created')->nullable();
            $table->string('serial_number', 15)->nullable();
            $table->text('serial_number_new')->nullable();
            $table->integer('consolidated_invoice_id')->nullable();
            $table->integer('report_cron_flag')->nullable();
            $table->integer('parser_request_id')->nullable();
            $table->string('static_BU', 250)->nullable();
            $table->decimal('bu_amount', 10, 2)->nullable();
            $table->decimal('reverse_bu_amount', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_invoice');
    }
};
