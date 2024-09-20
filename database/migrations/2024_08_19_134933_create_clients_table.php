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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('manager_id')->nullable();
            $table->string('organization')->nullable();
            $table->string('business_name')->nullable();
            $table->enum('is_enable', ['0', '1', '2'])->nullable();
            $table->enum('profile_approve', ['Yes', 'No'])->nullable();
            $table->dateTime('profile_approved_date')->nullable();
            $table->tinyInteger('profile_status')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->smallInteger('country')->nullable();
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
        Schema::dropIfExists('clients');
    }
};
