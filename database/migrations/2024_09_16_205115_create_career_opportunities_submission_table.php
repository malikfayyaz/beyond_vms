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
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('consultants')->onDelete('cascade');
            $table->foreignId('career_opportunity_id')->constrained('career_opportunities')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('hire_type_id')->nullable();
            $table->decimal('markup', 10, 2);
            $table->decimal('actuall_markup', 10, 2);
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
            $table->enum('ot_exempt_position', ['no', 'yes'])->default('no');
            $table->enum('require_employment_visa_sponsorship', ['no', 'yes'])->default('no');
            $table->enum('is_legally_authorized', ['no', 'yes'])->default('no');
            $table->enum('remote_contractor', ['no', 'yes'])->default('no');
            $table->enum('retiree', ['no', 'yes'])->default('no');
            $table->text('capacity');
            $table->enum('willing_relocate', ['no', 'yes'])->default('no');
            $table->unsignedBigInteger('emp_msp_account_mngr')->nullable();
            $table->tinyInteger('resume_status')->nullable();
            $table->dateTime('shortlisted_date')->nullable();
            $table->string('resume', 255)->nullable();
            $table->string('optional_document', 255)->nullable();
            $table->tinyInteger('release_to_client')->nullable();
            $table->tinyInteger('nda_completed')->default(0);
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->string('rejected_type', 111)->nullable();
            $table->string('reason_for_rejection', 512)->nullable();
            $table->text('note_for_rejection')->nullable();
            $table->text('notes')->nullable();
            $table->date('estimate_start_date');
            $table->unsignedBigInteger('rehired_status_by')->nullable();
            $table->text('rehire_comments')->nullable();
            $table->dateTime('rehire_comments_date')->nullable();
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
