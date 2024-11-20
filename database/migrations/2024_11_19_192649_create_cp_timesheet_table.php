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
        Schema::create('cp_timesheet', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id');
            $table->integer('parent_invoice_id');
            $table->integer('format_option');
            $table->enum('created_from', ['', 'MSP', 'Candidate', 'Vendor', 'Mobile']);
            $table->enum('submitted_from', ['', 'MSP', 'Candidate', 'Vendor', 'Mobile']);
            $table->enum('type_of_timesheet', ['', 'California', 'Non-California', 'No-Project']);
            $table->integer('sub_type_of_timesheet');
            $table->integer('location_id');
            $table->integer('contract_id');
            $table->integer('candidate_id');
            $table->integer('client_id');
            $table->integer('approval_manager');
            $table->integer('client_config_type');
            $table->date('invoice_start_date');
            $table->date('invoice_end_date');
            $table->string('invoice_duration', 25);
            $table->date('invoice_start_date_display');
            $table->date('invoice_end_date_display');
            $table->integer('creation_week_number');
            $table->integer('creation_year');
            $table->decimal('total_regular_hours', 10, 2);
            $table->decimal('total_overtime_hours', 10, 2);
            $table->decimal('total_doubletime_hours', 10, 2);
            $table->decimal('total_hours', 10, 2);
            $table->decimal('new_total_hours', 10, 2)->nullable();
            $table->decimal('total_100scale_regular_hours', 10, 2);
            $table->decimal('total_100scale_overtime_hours', 10, 2);
            $table->decimal('total_100scale_doubletime_hours', 10, 2);
            $table->decimal('total_100scale_hours', 10, 2);
            $table->decimal('new_100scale_total_hours', 10, 2);
            $table->decimal('leave_total_hours', 10, 2);
            $table->decimal('regular_payrate', 10, 2);
            $table->decimal('total_regular_payrate', 10, 2);
            $table->decimal('overtime_payrate', 10, 2);
            $table->decimal('total_overtime_payrate', 10, 2);
            $table->decimal('doubletime_payrate', 10, 2);
            $table->decimal('total_doubletime_payrate', 10, 2);
            $table->decimal('total_payrate', 10, 2);
            $table->decimal('new_total_payrate', 10, 2)->nullable();
            $table->text('notes');
            $table->decimal('regular_billrate', 10, 2);
            $table->decimal('total_regular_billrate', 10, 2);
            $table->decimal('overtime_billrate', 10, 2);
            $table->decimal('total_overtime_billrate', 10, 2);
            $table->decimal('doubletime_billrate', 10, 2);
            $table->decimal('total_doubletime_billrate', 10, 2);
            $table->decimal('total_billrate', 10, 2);
            $table->decimal('new_totall_billrate', 10, 2)->nullable();
            $table->decimal('overtime_markup', 10, 2);
            $table->decimal('doubletime_markup', 10, 2);
            $table->decimal('total_markup', 10, 2);
            $table->integer('accept_rejected_by');
            $table->enum('accept_rejected_by_type', ['', 'Client', 'MSP', 'Vendor', 'Consultant']);
            $table->string('reason_of_rejection', 255);
            $table->text('rejection_notes');
            $table->dateTime('rejection_date_time');
            $table->dateTime('approve_date_time');
            $table->integer('timesheet_status');
            $table->boolean('cronjob_email_status')->default(0);
            $table->integer('timesheet_sub_status');
            $table->boolean('invoice_generated');
            $table->date('date_created');
            $table->dateTime('date_updated');
            $table->dateTime('date_submitted');
            $table->integer('village');
            $table->integer('sub_village');
            $table->enum('meal_period_law', ['', 'Yes', 'No'])->nullable();
            $table->enum('break_period_law', ['', 'Yes', 'No'])->nullable();
            $table->integer('no_of_missed_meal_break')->nullable();
            $table->decimal('meal_penality_payrate', 10, 2)->nullable();
            $table->decimal('meal_penality_billrate', 10, 2)->nullable();
            $table->integer('no_of_missed_paid_break')->nullable();
            $table->decimal('paid_break_penality_payrate', 10, 2)->nullable();
            $table->decimal('paid_break_penality_billrate', 10, 2)->nullable();
            $table->string('cd_memo_type', 100);
            $table->string('cd_memo_reason', 100);
            $table->text('cd_memo_notes');
            $table->integer('rate_type_for_modified');
            $table->integer('rejected_child');
            $table->enum('timesheet_current_type', ['Original', 'Modified', 'Modified Original']);
            $table->integer('timesheet_current_reverse');
            $table->string('conso_serial_number', 100);
            $table->integer('parser_request_id');
            $table->integer('is_matched');
            $table->text('unmatched_reason');
            $table->string('candidate_first_name', 255);
            $table->string('candidate_last_name', 255);
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
        Schema::dropIfExists('cp_timesheet');
    }
};
