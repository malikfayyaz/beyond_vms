<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('cp_timesheet_details', function (Blueprint $table) {
        $table->id();
        $table->integer('timesheet_id');
        $table->integer('contract_id');
        $table->integer('candidate_id');
        $table->integer('client_config_type');
        $table->string('project_task_type', 25);
        $table->integer('project_code_id');
        $table->integer('creation_week_number');
        $table->integer('creation_year');
        $table->date('creation_day');
        $table->enum('hours_type', ['Regular Hour', 'Time Off', 'Over Time', 'Double Time']);
        $table->decimal('no_of_hours', 10, 2);
        $table->decimal('no_100scale_of_hours', 10, 2);
        $table->decimal('total_payrate', 10, 2);
        $table->decimal('total_billrate', 10, 2);
        $table->decimal('total_vendor_billrate', 10, 2);
        $table->integer('leave_reason');
        $table->text('comment')->nullable();
        $table->date('date_created');
        $table->timestamp('date_updated')->useCurrent();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cp_timesheet_details');
    }
};
