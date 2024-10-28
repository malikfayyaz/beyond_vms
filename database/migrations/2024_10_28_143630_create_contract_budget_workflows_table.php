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
        Schema::create('contract_budget_workflows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('career_opportunities_contract')->onDelete('cascade');          // Foreign key for offer
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');        // Foreign key for client (hiring manager)
            $table->unsignedBigInteger('workflow_id')->default(0);
            $table->unsignedBigInteger('request_id')->default(0);
            $table->unsignedBigInteger('approval_role_id'); // Foreign key for approval role
            $table->boolean('bulk_approval')->default(0);
            $table->string('approval_number')->nullable();
            $table->string('status')->default('Pending');
            $table->timestamp('status_time')->useCurrent();  // Tracks the time of the status
            $table->boolean('approval_required')->default(1);
            $table->timestamp('approved_datetime')->nullable(); // Time of approval
            $table->unsignedBigInteger('rejection_id')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('approve_reject_by')->nullable();
            $table->string('approve_reject_type')->nullable();
            $table->string('approve_reject_from')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('machine_user_name')->nullable();
            $table->string('approval_doc')->nullable();
            $table->text('approval_notes')->nullable();
            $table->boolean('email_sent')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_budget_workflows');
    }
};
