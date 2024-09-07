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
            $table->string('division_id'); // Assuming division is a string
            $table->string('branch_id'); // Assuming branch is a string
            $table->string('zone_id'); // Assuming zone is a string
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
