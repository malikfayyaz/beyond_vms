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
            $table->decimal('location_tax', 10, 2)->default(0.00)->after('estimate_cost'); // Add location_tax column after an existing column
            $table->tinyInteger('verification_status_vendor')->default(0)->after('status');
            $table->tinyInteger('verification_status')->default(0)->after('verification_status_vendor');
            $table->dateTime('vendor_bg_date')->nullable()->after('verification_status');
            $table->tinyInteger('bg_reviewed_msp')->default(0)->after('vendor_bg_date');
            $table->tinyInteger('markcompleted_by')->default(0)->after('bg_reviewed_msp');
            $table->dateTime('markcompleted_date')->nullable()->after('markcompleted_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('career_opportunities_workorder', function (Blueprint $table) {
            $table->dropColumn('location_tax','verification_status_vendor');
        });
    }
};
