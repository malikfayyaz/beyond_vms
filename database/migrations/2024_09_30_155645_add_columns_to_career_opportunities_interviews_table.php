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
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            $table->integer('status')->nullable()->after('event_name');
            $table->time('start_time')->nullable();    
            $table->time('end_time')->nullable();
            $table->unsignedBigInteger('created_by_user')->nullable(); 
            $table->text('interview_detail')->nullable()->after('interview_type');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_interviews', function (Blueprint $table) {
            $table->dropColumn(['status', 'start_time', 'end_time','created_by_user','interview_detail']);
        });
    }
};
