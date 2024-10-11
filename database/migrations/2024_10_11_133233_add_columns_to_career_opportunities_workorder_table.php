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
            $table->timestamp('rejection_date')->nullable();
            $table->text('reason_rejection')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_workorder', function (Blueprint $table) {
            $table->dropColumn('rejection_date');
            $table->dropColumn('reason_rejection');
        });
    }
};
