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
            $table->tinyInteger('member_type')->default(0)->comment('0: Regular member, 3: Parent member')->after('user_id');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('Parent Vendor ID')->after('member_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['member_type', 'parent_id']);
        });
    }
};
