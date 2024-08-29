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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('staf_type');
            $table->string('member_access');
            $table->tinyInteger('admin_status');
            $table->string('profile_image');
            $table->text('description');
            $table->dateTime('last_login');
            $table->tinyInteger('country');
            $table->string('date_format_php');
            $table->string('date_format_js');
            $table->string('phone');
            $table->string('language');
            $table->tinyInteger('portal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
