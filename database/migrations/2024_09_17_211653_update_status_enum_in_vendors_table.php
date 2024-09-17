<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStatusEnumInVendorsTable extends Migration
{
    public function up()
    {
        // Modify the ENUM values for the 'status' column
        DB::statement("ALTER TABLE vendors MODIFY COLUMN status ENUM('active', 'under review', 'inactive') DEFAULT 'inactive'");
    }

    public function down()
    {
        // Revert back to the original ENUM values
        DB::statement("ALTER TABLE vendors MODIFY COLUMN status ENUM('Active', 'Under Review', 'Inactive') DEFAULT 'Active'");
    }
}
