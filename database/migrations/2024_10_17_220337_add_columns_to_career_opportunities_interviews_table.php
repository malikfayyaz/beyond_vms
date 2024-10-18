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
            $table->unsignedBigInteger('interview_completed_reason')->nullable();
            $table->date('interview_completed_date')->nullable(); 
            $table->text('interview_completed_notes')->nullable();
            $table->tinyInteger('rejected_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            $table->tinyInteger('rejected_type')->nullable(false)->change();
            $table->dropColumn(['interview_completed_reason', 'interview_completed_date', 'interview_completed_notes']);
        });
    }
};
