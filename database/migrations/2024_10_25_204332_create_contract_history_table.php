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
        Schema::create('contract_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('career_opportunities_contract')->onDelete('cascade'); 
            $table->integer('ext_req_id')->nullable();
            $table->foreignId('workorder_id')->constrained('career_opportunities_workorder')->onDelete('cascade'); 
            $table->foreignId('offer_id')->constrained('career_opportunities_offer')->onDelete('cascade'); 
            $table->integer('submission_id')->nullable();
            $table->integer('job_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('candidate_id')->nullable();
            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->integer('contract_status')->default(0);
            $table->integer('onboard_changed_end_date')->nullable();
            $table->decimal('wo_bill_rate', 10, 3)->nullable();
            $table->decimal('wo_pay_rate', 10, 3)->nullable();
            $table->decimal('wo_over_time', 10, 3)->nullable();
            $table->decimal('wo_double_time', 10, 3)->nullable();
            $table->decimal('wo_client_over_time', 10, 3)->nullable();
            $table->decimal('wo_client_double_time', 10, 3)->nullable();
            $table->decimal('vendor_bill_rate', 10, 2)->nullable();
            $table->decimal('vendor_overtime_rate', 10, 2)->nullable();
            $table->decimal('vendor_doubletime_rate', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_history');
    }
};
