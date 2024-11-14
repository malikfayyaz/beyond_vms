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
        Schema::table('contract_rates', function (Blueprint $table) {
            $table->decimal('markup', 8, 2)->default(0)->after('vendor_doubletime_rate')->comment('Markup percentage for contract rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contract_rates', function (Blueprint $table) {
            $table->dropColumn('markup');
        });
    }
};
