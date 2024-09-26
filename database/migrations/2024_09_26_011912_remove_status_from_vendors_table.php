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
        Schema::table('vendors', function (Blueprint $table) {
            // Remove the 'status' column
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Add the 'status' column back (if rolling back the migration)
            $table->string('status')->nullable(); // Adjust data type and options as per your original table structure
        });
    }
};
