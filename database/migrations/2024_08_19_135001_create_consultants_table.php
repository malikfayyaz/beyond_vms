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
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->string('organization')->nullable();
            $table->string('business_name')->nullable();
            $table->enum('is_enable', ['0', '1','2']);
            $table->enum('profile_approve', ['Yes', 'No']);
            $table->dateTime('profile_approved_date');
            $table->tinyInteger('profile_status');
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
        Schema::dropIfExists('consultants');
    }
};
