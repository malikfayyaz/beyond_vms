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
            $table->id();
            $table->integer('division'); // Assuming division_id references another table
            $table->integer('branch'); // Assuming branch_id references another table
            $table->integer('zone'); // Assuming zone_id references another table
            $table->integer('bu'); // Business Unit field
            $table->integer('status'); // Status field
            $table->timestamps();

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
