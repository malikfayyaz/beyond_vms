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
            $table->string('withdraw_reason')->nullable(); // Example: nullable string column for withdrawal reason
            $table->dateTime('date_modified')->nullable(); // Example: integer column for modified by user ID
            $table->dateTime('offer_rejection_date')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_offer', function (Blueprint $table) {
            $table->dropColumn('withdraw_reason');
            $table->dropColumn('date_modified');
            $table->dropColumn('offer_rejection_date');
        });
    }
};
