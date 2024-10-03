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
        Schema::create('workorder_backgrounds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workorder_id'); // Foreign key
            $table->foreign('workorder_id')->references('id')->on('career_opportunities_workorder')->onDelete('cascade');
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('markcompleted_by')->nullable();
            $table->dateTime('markcompleted_date')->nullable();
            $table->boolean('code_of_conduct')->default(false);
            $table->boolean('data_privacy')->default(false);
            $table->boolean('non_disclosure')->default(false);
            $table->boolean('criminal_background')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workorder_backgrounds');
    }
};
