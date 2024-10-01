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
            $table->unsignedBigInteger('created_by_user')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            $table->dropColumn('created_by_user');
        });
    }
};
