<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCareerOpportunitiesOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('career_opportunities_offer', function (Blueprint $table) {
            $table->id(); // This will create an auto-incrementing id column
            $table->foreignId('submission_id')->constrained('career_opportunities_submission')->onDelete('cascade'); 
            $table->foreignId('career_opportunity_id')->constrained('career_opportunities')->onDelete('cascade'); 
            $table->foreignId('candidate_id')->constrained('consultants')->onDelete('cascade'); 
            $table->foreignId('hiring_manager_id')->constrained('clients')->onDelete('cascade'); 
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');  
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('created_by_type')->nullable();
            $table->unsignedBigInteger('modified_by_id')->nullable();
            $table->unsignedBigInteger('modified_by_type')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->dateTime('offer_accept_date')->nullable();
            $table->decimal('offer_pay_rate', 10, 2)->default(0.00);
            $table->decimal('offer_bill_rate', 10, 2)->default(0.00);
            $table->decimal('over_time', 10, 2)->default(0.00);
            $table->decimal('double_time', 10, 2)->default(0.00);
            $table->decimal('client_overtime', 10, 2)->default(0.00);
            $table->decimal('client_doubletime', 10, 2)->default(0.00);
            $table->decimal('vendor_bill_rate', 10, 2)->default(0.00);
            $table->decimal('vendor_overtime', 10, 2)->default(0.00);
            $table->decimal('vendor_doubletime', 10, 2)->default(0.00);
            $table->decimal('estimate_cost', 10, 2)->default(0.00);
            $table->decimal('markup', 10, 2)->default(0.00);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('remote_option', ['Yes', 'No'])->default('No');
            $table->text('notes')->nullable();
            $table->enum('release_vendor', ['1', '0'])->default('0');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('career_opportunities_offer');
    }
}
