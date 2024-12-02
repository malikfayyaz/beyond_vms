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
        Schema::create('cp_timesheet_tax_amount', function (Blueprint $table) {
            $table->id();
            $table->integer('timesheet_id')->nullable()->index();
            $table->integer('cost_center_config')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->decimal('msp_percentage', 10, 2)->nullable();
            $table->decimal('penality_msp_percentage', 10, 2)->nullable();
            $table->decimal('tax_percentage', 10, 2)->nullable();
            $table->decimal('regular_billrate', 10, 2)->nullable();
            $table->decimal('total_regular_billrate', 10, 2)->nullable();
            $table->decimal('overtime_billrate', 10, 2)->nullable();
            $table->decimal('total_overtime_billrate', 10, 2)->nullable();
            $table->decimal('doubletime_billrate', 10, 2)->nullable();
            $table->decimal('total_doubletime_billrate', 10, 2)->nullable();
            $table->decimal('total_billrate', 10, 2)->nullable();
            $table->decimal('regular_payrate', 10, 2)->nullable();
            $table->decimal('total_regular_payrate', 10, 2)->nullable();
            $table->decimal('overtime_payrate', 10, 2)->nullable();
            $table->decimal('total_overtime_payrate', 10, 2)->nullable();
            $table->decimal('doubletime_payrate', 10, 2)->nullable();
            $table->decimal('total_doubletime_payrate', 10, 2)->nullable();
            $table->decimal('total_payrate', 10, 2)->nullable();
            $table->decimal('total_regular_billrate_tax', 10, 2)->nullable();
            $table->decimal('total_regular_billrate_tax_amount', 10, 2)->nullable();
            $table->decimal('total_overtime_billrate_tax', 10, 2)->nullable();
            $table->decimal('total_overtime_billrate_tax_amount', 10, 2)->nullable();
            $table->decimal('total_doubletime_billrate_tax', 10, 2)->nullable();
            $table->decimal('total_doubletime_billrate_tax_amount', 10, 2)->nullable();
            $table->decimal('total_billrate_tax', 10, 2)->nullable();
            $table->decimal('total_billrate_tax_amount', 10, 2)->nullable();
            $table->decimal('regular_vendor_billrate', 10, 2)->nullable();
            $table->decimal('overtime_vendor_billrate', 10, 2)->nullable();
            $table->decimal('doubletime_vendor_billrate', 10, 2)->nullable();
            $table->decimal('total_regular_vendor_billrate', 10, 2)->nullable();
            $table->decimal('total_overtime_vendor_billrate', 10, 2)->nullable();
            $table->decimal('total_doubletime_vendor_billrate', 10, 2)->nullable();
            $table->decimal('total_vendor_billrate', 10, 2)->nullable();
            $table->decimal('meal_penality_billrate', 10, 2)->nullable();
            $table->decimal('meal_penality_billrate_tax_amount', 10, 2)->nullable();
            $table->decimal('meal_penality_billrate_fee', 10, 2)->nullable();
            $table->decimal('paid_break_penality_billrate', 10, 2)->nullable();
            $table->decimal('paid_break_penality_billrate_tax_amount', 10, 2)->nullable();
            $table->decimal('paid_break_penality_billrate_fee', 10, 2)->nullable();
            $table->decimal('msp_regular_fee', 10, 2)->nullable();
            $table->decimal('msp_overtime_fee', 10, 2)->nullable();
            $table->decimal('msp_doubletime_fee', 10, 2)->nullable();
            $table->decimal('msp_total_fee', 10, 2)->nullable();
            $table->integer('gl_id')->nullable();
            $table->integer('dept_id')->nullable();
            $table->integer('cost_center_id')->nullable();
            $table->tinyInteger('updated_flag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cp_timesheet_tax_amount');
    }
};
