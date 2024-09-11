<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerOpportunitiesBuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_opportunities_bu', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('career_opportunity_id'); // Foreign key to career_opportunities table
            $table->foreignId('bu_unit')->constrained('generic_data')->onDelete('cascade');
            $table->integer('percentage');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('career_opportunity_id')
                ->references('id')
                ->on('career_opportunities')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_opportunities_bu');
    }
}
