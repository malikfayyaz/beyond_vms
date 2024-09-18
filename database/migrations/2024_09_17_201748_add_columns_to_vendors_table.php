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
        Schema::table('vendors', function (Blueprint $table) {
            // Use 191 for email length to avoid index size issues
            $table->string('email', 191)->unique(); 
            $table->integer('member_access');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Drop the columns to reverse the migration
            $table->dropColumn('email');
            $table->dropColumn('member_access');
        });
    }
};
