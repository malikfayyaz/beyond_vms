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
        Schema::table('career_opportunities_offer', function (Blueprint $table) {
            $table->text('reason_rejection')->nullable(); // $reason_for_rejection
            $table->unsignedBigInteger('rejected_by')->nullable(); // $userid
            $table->tinyInteger('rejected_type')->default(1); // Default to 1
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_offer', function (Blueprint $table) {
            $table->dropColumn('reason_rejection');
            $table->dropColumn('rejected_by');
            $table->dropColumn('rejected_type');
        });
    }
};
