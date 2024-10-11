<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            // Rename the existing column
            $table->renameColumn('created_by_user', 'created_by_portal');
            $table->unsignedBigInteger('created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            // Revert the column name change
            $table->renameColumn('created_by_user', 'created_by_portal');
            $table->dropColumn('created_by');
        });
    }
};
