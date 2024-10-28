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
        Schema::table('career_opportunities_contract', function (Blueprint $table) {
            $table->text('termination_feedback')->nullable()->after('termination_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_contract', function (Blueprint $table) {
            $table->dropColumn('termination_feedback');
        });
    }
};
