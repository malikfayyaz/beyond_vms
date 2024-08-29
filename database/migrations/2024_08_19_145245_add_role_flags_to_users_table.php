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
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_admin')->default(0);
            $table->tinyInteger('is_client')->default(0);
            $table->tinyInteger('is_vendor')->default(0);
            $table->tinyInteger('is_consultant')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'is_client', 'is_vendor', 'is_consultant']);
        });
    }
};
