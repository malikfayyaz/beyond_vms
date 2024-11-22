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
          Schema::create('timesheet_costcenter_temp', function (Blueprint $table) {
            $table->id(); 
            $table->integer('timesheet_id')->nullable(); 
            $table->enum('type', ['Regular Hour', 'Over Time', 'Double Time'])->nullable();
            $table->integer('cost_center')->nullable();
            $table->decimal('hours', 12, 2)->nullable(); 
            $table->date('creation_day')->nullable();
            $table->text('notes')->nullable(); 
            $table->integer('leave_reason')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheet_costcenter_temp');
    }
};
