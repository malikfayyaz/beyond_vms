<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpTimesheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_timesheets', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->default(0);
            $table->integer('parent_invoice_id')->nullable();
            $table->integer('format_option')->nullable();
            $table->integer('created_from')->nullable();
            $table->integer('submitted_from')->nullable();
            $table->enum('type_of_timesheet', ['', 'California', 'Non-California', 'No-Project']);
            $table->integer('sub_type_of_timesheet')->nullable();
            $table->integer('location_id')->nullable();
            $table->integer('contract_id')->nullable();
            $table->integer('candidate_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->integer('approval_manager')->nullable();
            $table->integer('client_config_type')->nullable();
            $table->date('invoice_start_date')->nullable();
            $table->date('invoice_end_date')->nullable();
            $table->string('invoice_duration', 25)->nullable();
            $table->date('invoice_start_date_display')->nullable();
            $table->date('invoice_end_date_display')->nullable();
            $table->integer('creation_week_number')->nullable();
            $table->integer('creation_year')->nullable();
            $table->decimal('total_regular_hours', 10, 2)->default(0);
            $table->decimal('total_overtime_hours', 10, 2)->default(0);
            $table->decimal('total_doubletime_hours', 10, 2)->default(0);
            $table->decimal('total_hours', 10, 2)->default(0);
            $table->decimal('new_total_hours', 10, 2)->default(0);
            $table->decimal('total_100scale_regular_hours', 10, 2)->default(0);
            $table->decimal('total_100scale_overtime_hours', 10, 2)->default(0);
            $table->decimal('total_100scale_doubletime_hours', 10, 2)->default(0);
            $table->decimal('total_100scale_hours', 10, 2)->default(0);
            $table->decimal('new_100scale_total_hours', 10, 2)->default(0);
            $table->decimal('leave_total_hours', 10, 2)->default(0);
            $table->decimal('regular_payrate', 10, 2)->default(0);
            $table->decimal('total_regular_payrate', 10, 2)->default(0);
            $table->decimal('overtime_payrate', 10, 2)->default(0);
            $table->decimal('total_overtime_payrate', 10, 2)->default(0);
            $table->decimal('doubletime_payrate', 10, 2)->default(0);
            $table->decimal('total_doubletime_payrate', 10, 2)->default(0);
            $table->decimal('total_payrate', 10, 2)->default(0);
            $table->decimal('new_total_payrate', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->decimal('regular_billrate', 10, 2)->default(0);
            $table->decimal('total_regular_billrate', 10, 2)->default(0);
            $table->decimal('overtime_billrate', 10, 2)->default(0);
            $table->decimal('total_overtime_billrate', 10, 2)->default(0);
            $table->decimal('doubletime_billrate', 10, 2)->default(0);
            $table->decimal('total_doubletime_billrate', 10, 2)->default(0);
            $table->decimal('total_billrate', 10, 2)->default(0);
            $table->decimal('new_totall_billrate', 10, 2)->default(0);
            $table->decimal('overtime_markup', 10, 2)->default(0);
            $table->decimal('doubletime_markup', 10, 2)->default(0);
            $table->decimal('total_markup', 10, 2)->default(0);
            $table->integer('accept_rejected_by')->nullable();
            $table->integer('accept_rejected_by_type')->nullable();
            $table->string('reason_of_rejection', 255)->nullable();
            $table->text('rejection_notes')->nullable();
            $table->dateTime('rejection_date_time')->nullable();
            $table->dateTime('approve_date_time')->nullable();
            $table->integer('timesheet_status')->nullable();
            $table->boolean('cronjob_email_status')->default(0);
            $table->integer('timesheet_sub_status')->nullable();
            $table->boolean('invoice_generated')->nullable();
            $table->dateTime('date_submitted')->nullable();
            $table->integer('village')->nullable();
            $table->integer('sub_village')->nullable();
            $table->enum('meal_period_law', ['', 'Yes', 'No'])->nullable();
            $table->enum('break_period_law', ['', 'Yes', 'No'])->nullable();
            $table->integer('no_of_missed_meal_break')->nullable();
            $table->decimal('meal_penality_payrate', 10, 2)->nullable();
            $table->decimal('meal_penality_billrate', 10, 2)->nullable();
            $table->integer('no_of_missed_paid_break')->nullable();
            $table->decimal('paid_break_penality_payrate', 10, 2)->nullable();
            $table->decimal('paid_break_penality_billrate', 10, 2)->nullable();
            $table->string('cd_memo_type', 100)->nullable();
            $table->string('cd_memo_reason', 100)->nullable();
            $table->text('cd_memo_notes')->nullable();
            $table->integer('rate_type_for_modified')->nullable();
            $table->integer('rejected_child')->nullable();
            $table->enum('timesheet_current_type', ['Original', 'Modified', 'Modified Original']);
            $table->integer('timesheet_current_reverse')->nullable();
            $table->string('conso_serial_number', 100)->nullable();
            $table->integer('parser_request_id')->nullable();
            $table->integer('is_matched')->nullable();
            $table->text('unmatched_reason')->nullable();
            $table->string('candidate_first_name', 255)->nullable();
            $table->string('candidate_last_name', 255)->nullable();
            $table->dateTime('cronjob_email_datetime')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_timesheets');
    }
};
