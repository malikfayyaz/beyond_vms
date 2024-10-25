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
        Schema::create('contract_additional_budget', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('contract_id')->constrained('career_opportunities_contract')->onDelete('cascade'); 
            $table->unsignedBigInteger('created_by'); 
            $table->enum('created_by_type', ['', 'MSP', 'Client']); 
            $table->decimal('amount', 10, 2); 
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('additional_budget_reason');
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->nullable()->default('Pending'); 
            $table->datetime('approval_rejection_date')->nullable(); 
            $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_additional_budget');
    }
};
