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
        Schema::table('admins', function (Blueprint $table) {
            // Add email column
            $table->string('email')->unique(); 

            // Add status column
            $table->enum('status', ['active', 'inactive'])->default('active'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
           
            // Drop email and status columns
            $table->dropColumn('email');
            $table->dropColumn('status');
        });
    }
};
