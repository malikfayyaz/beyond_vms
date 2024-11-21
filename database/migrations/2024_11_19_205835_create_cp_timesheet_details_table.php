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
        $table->integer('contract_id')->default(0);
        $table->integer('candidate_id')->default(0);
        $table->integer('client_config_type')->default(0);
        $table->string('project_task_type', 25)->nullable();
        $table->integer('project_code_id')->default(0);
        $table->integer('creation_week_number')->default(0);
        $table->integer('creation_year')->default(0);
        $table->date('creation_day')->nullable();
        $table->enum('hours_type', ['Regular Hour', 'Time Off', 'Over Time', 'Double Time']);
        $table->decimal('no_of_hours', 10, 2)->default(0);
        $table->decimal('no_100scale_of_hours', 10, 2)->default(0);
        $table->decimal('total_payrate', 10, 2)->default(0);
        $table->decimal('total_billrate', 10, 2)->default(0);
        $table->decimal('total_vendor_billrate', 10, 2)->default(0);
        $table->integer('leave_reason')->nullable();
        $table->text('comment')->nullable();
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
