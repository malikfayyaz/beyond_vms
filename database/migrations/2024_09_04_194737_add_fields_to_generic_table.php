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
        Schema::table('generic_data', function (Blueprint $table) {
            $table->foreignId('symbol_id')->nullable()->constrained('settings')->onDelete('cascade')->after('country_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generic_data', function (Blueprint $table) {
            $table->dropForeign(['symbol_id']);
    
            // Now you can safely drop the column
            $table->dropColumn('symbol_id');
        });
    }
};
