<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerOpportunitiesSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_opportunities_submission', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing id column
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');  
            $table->unsignedBigInteger('created_by_user');
            $table->foreignId('client_id')->constrained('client')->onDelete('cascade');
            $table->foreignId('candidate_Id')->constrained('consultant')->onDelete('cascade');
            $table->foreignId('career_opportunity_id')->constrained('career_opportunities')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->integer('submission_type')->comment('1=>markup,0=>nomarkup');
            $table->unsignedBigInteger('hire_type_id');
            $table->decimal('makrup', 10, 2);
            $table->decimal('actuall_makrup', 10, 2);
            $table->decimal('vendor_bill_rate', 10, 2);  
            $table->decimal('candidate_pay_rate', 10, 2);
            $table->decimal('bill_rate', 10, 2);
            $table->decimal('bill_rate_new', 10, 2);
            $table->decimal('bill_rate_new_overtime', 10, 2);
            $table->decimal('bill_rate_new_doubletime', 10, 2);
            $table->decimal('over_time_rate', 10, 2);
            $table->decimal('client_over_time_rate', 10, 2);
            $table->decimal('double_time_rate', 10, 2);
            $table->decimal('client_double_time_rate', 10, 2);
            $table->string('ot_exempt_position', 50);
            $table->string('require_employment_visa_sponsorship', 50);
            $table->string('is_legally_authorized', 50);
            $table->text('current_location');
            $table->string('remote_contractor', 10);
            $table->string('retiree', 50);
            $table->text('capacity');
            $table->string('contingent_worker', 50);
            $table->string('willing_relocate', 50);
            $table->unsignedBigInteger('emp_msp_account_mngr');
            $table->enum('job_sub_type', ['Per Hour', 'Per Day', 'Per Week', 'Per Month']);
            $table->tinyInteger('resume_status');
            $table->dateTime('shortlisted_date')->nullable();
            $table->string('resume', 255);
            $table->string('optional_document', 255);
            $table->tinyInteger('release_to_client');
            $table->tinyInteger('nda_completed')->default(0);
            $table->unsignedBigInteger('rejected_by');
            $table->string('rejected_type', 111);
            $table->string('reason_for_rejection', 512);
            $table->text('note_for_rejection');
            $table->text('notes');
            $table->date('estimate_start_date');
            $table->unsignedBigInteger('rehired_status_by');
            $table->text('rehire_comments');
            $table->dateTime('rehire_comments_date');
            $table->enum('rehire_by_type', ['admin', 'Client']);
            $table->dateTime('date_rejected')->nullable();
            $table->string('virtual_city', 50);
            $table->text('interview_notes');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_opportunities_submission');
    }
}
