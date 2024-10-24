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
        Schema::table('career_opportunities_workorder', function (Blueprint $table) {
            $table->text('job_level_notes')->nullable()->after('job_type'); 
                   });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_workorder', function (Blueprint $table) {
            $table->dropColumn('job_level_notes');
                    });
    }
};
