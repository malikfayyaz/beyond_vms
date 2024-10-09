<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contract_id');
            $table->unsignedBigInteger('workorder_id');
            $table->decimal('client_bill_rate', 10, 2)->nullable();
            $table->decimal('client_overtime_rate', 10, 2)->nullable();
            $table->decimal('client_doubletime_rate', 10, 2)->nullable();
            $table->decimal('candidate_pay_rate', 10, 2)->nullable();
            $table->decimal('candidate_overtime_rate', 10, 2)->nullable();
            $table->decimal('candidate_doubletime_rate', 10, 2)->nullable();
            $table->decimal('vendor_bill_rate', 10, 2)->nullable();
            $table->decimal('vendor_overtime_rate', 10, 2)->nullable();
            $table->decimal('vendor_doubletime_rate', 10, 2)->nullable();
            $table->date('effective_date')->nullable();
            $table->tinyInteger('request_type')->default(0);
            $table->unsignedBigInteger('history_id')->default(0);

            $table->timestamps();

            // Foreign key relationships
            $table->foreign('contract_id')->references('id')->on('career_opportunities_contract')->onDelete('cascade');
            $table->foreign('workorder_id')->references('id')->on('career_opportunities_workorder')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_rates');
    }
}
