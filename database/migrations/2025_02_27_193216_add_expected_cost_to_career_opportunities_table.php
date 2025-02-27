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
        Schema::table('career_opportunities', function (Blueprint $table) {
            $table->decimal('expected_cost', 10, 2)->nullable()->after('job_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities', function (Blueprint $table) {
            $table->dropColumn('expected_cost');
        });
    }
};
