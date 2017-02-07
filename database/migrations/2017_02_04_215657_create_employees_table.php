<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Payroll\Factory\Employee as EmployeeFactory;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('address');
            $table->float('salary')->nullable()->unsigned();
            $table->float('hourly_rate')->nullable()->unsigned();
            $table->float('commission_rate')->nullable()->unsigned();
            $table->enum('type', [EmployeeFactory::SALARIED, EmployeeFactory::HOURLY, EmployeeFactory::COMMISSION]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
