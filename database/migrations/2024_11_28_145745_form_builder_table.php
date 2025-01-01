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
        Schema::create('form_builder', function (Blueprint $table) {
            $table->id(); 
            $table->integer('type');  
            $table->longText('data'); 
            $table->enum('status', ['active', 'inactive']); 
            $table->unsignedBigInteger('created_by'); 
            $table->unsignedBigInteger('created_by_portal'); 
            $table->timestamps();  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_builder');
    }
};
