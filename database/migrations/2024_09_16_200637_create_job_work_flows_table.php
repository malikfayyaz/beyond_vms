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
        Schema::create('job_work_flows', function (Blueprint $table) {

            $table->id();
            $table->foreignId('job_id')->constrained('jobs')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->integer('workflow_id')->nullable();
            $table->integer('costcenter_id')->nullable();
            $table->integer('approval_role_id')->nullable();
            $table->integer('bulk_approval')->nullable();
            $table->integer('approval_number')->nullable();
            $table->Enum('status', ['Pending' , 'Approved' , 'Rejected'])->nullable();
            $table->dateTime('status_time')->nullable();
            $table->string('approval_required')->nullable();
            $table->dateTime('approved_datetime')->nullable();
            $table->integer('rejection_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('approve_reject_by')->nullable();
            $table->string('approve_reject_type')->nullable();
            $table->Enum('approve_reject_from', ['Portal','Email'])->nullable();
            $table->string('ip_address')->nullable();
            $table->string('machine_user_name')->nullable();
            $table->string('approval_doc')->nullable();
            $table->text('approval_notes')->nullable();
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_work_flows');
    }
};
