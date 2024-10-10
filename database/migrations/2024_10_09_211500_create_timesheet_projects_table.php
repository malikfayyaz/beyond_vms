<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimesheetProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timesheet_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('bu_id');
            $table->decimal('budget_percentage', 5, 2)->nullable();
            $table->decimal('billrate', 10, 2)->nullable();
            $table->decimal('overtime_billrate', 10, 2)->nullable();
            $table->decimal('doubletime_billrate', 10, 2)->nullable();
            $table->decimal('payrate', 10, 2)->nullable();
            $table->decimal('overtime_payrate', 10, 2)->nullable();
            $table->decimal('doubletime_payrate', 10, 2)->nullable();
            $table->decimal('vendor_billrate', 10, 2)->nullable();
            $table->decimal('vendor_overtime_billrate', 10, 2)->nullable();
            $table->decimal('vendor_doubletime_billrate', 10, 2)->nullable();
            $table->integer('approvers')->default(0);
            $table->date('effective_date')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            // Foreign key relationships
            $table->foreign('contract_id')->references('id')->on('career_opportunities_contract')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timesheet_projects');
    }
}

