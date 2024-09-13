<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerOpportunitiesWorkflowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_opportunities_workflows', function (Blueprint $table) {
            $table->id('workflow_id'); // Auto-incrementing primary key
            $table->string('member_type'); // Type of member
            $table->unsignedBigInteger('job_id'); // Foreign key to job
            $table->unsignedBigInteger('client_id'); // Foreign key to client
            $table->string('ref_backup')->nullable(); // Reference or backup field
            $table->boolean('bulk_approval')->default(0); // Boolean field for bulk approval
            $table->string('approval_type'); // Type of approval
            $table->string('job_status'); // Status of the job
            $table->boolean('email_sent')->default(0); // Email sent status
            $table->timestamp('status_time')->nullable(); // Timestamp for status change
            $table->timestamps(); // Laravel's default created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_opportunities_workflows');
    }
}

