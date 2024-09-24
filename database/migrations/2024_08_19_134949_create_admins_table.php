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
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username')->nullable();
            $table->string('staf_type')->nullable();
            $table->string('member_access')->nullable();
            $table->tinyInteger('admin_status')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->tinyInteger('country')->nullable();
            $table->string('date_format_php')->nullable();
            $table->string('date_format_js')->nullable();
            $table->string('phone')->nullable();
            $table->string('language')->nullable();
            $table->tinyInteger('portal')->nullable();
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
