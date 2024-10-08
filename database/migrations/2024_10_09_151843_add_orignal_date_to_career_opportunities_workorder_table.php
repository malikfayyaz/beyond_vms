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
        Schema::table('career_opportunities_workorder', function (Blueprint $table) {
            $table->date('original_start_date')->nullable()->after('end_date');
            $table->unsignedBigInteger('sourcing_type')->nullable()->after('original_start_date');
            $table->date('onboard_change_start_date')->nullable()->after('sourcing_type');
            $table->date('onboard_changed_end_date')->nullable()->after('onboard_change_start_date');
            $table->tinyInteger('on_board_status')->default(0)->after('onboard_changed_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_workorder', function (Blueprint $table) {
            $table->dropColumn('original_start_date','sourcing_type','on_board_status','onboard_change_start_date','onboard_changed_end_date');
        });
    }
};
