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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->integer('super_vendor_id');
            $table->string('organization');
            $table->string('business_name');
            $table->enum('status', ['Active', 'Under Review','Inactive']);
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
        Schema::dropIfExists('vendors');
    }
};
