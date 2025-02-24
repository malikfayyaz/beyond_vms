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
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            $table->date('interview_acceptance_date')->nullable(); 
            $table->text('acceptance_notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            $table->dropColumn('interview_acceptance_date');
            $table->dropColumn('acceptance_notes');
        });
    }
};
