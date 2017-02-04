<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->float('commission')->nullable()->unsigned();
            $table->enum('type', [\Payroll\Transaction\AddEmployee::SALARIED, \Payroll\Transaction\AddEmployee::HOURLY, \Payroll\Transaction\AddEmployee::COMMISSION]);
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
