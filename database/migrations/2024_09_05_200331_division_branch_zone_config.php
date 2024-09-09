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
        Schema::create('division_branch_zone_config', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->foreignId('division_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained('generic_data')->onDelete('cascade');
            $table->foreignId('bu_id')->constrained('generic_data')->onDelete('cascade'); // Business Unit field, using `generic_data` table
            $table->enum('status', ['Active', 'Inactive']); // Status field
            $table->timestamps(); // Created_at and Updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_branch_zone_config');
    }
};
