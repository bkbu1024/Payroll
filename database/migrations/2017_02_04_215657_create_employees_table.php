<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\PaymentMethod\Factory as PaymentMethodFactory;

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
            $table->enum('payment_classification', [EmployeeFactory::SALARIED, EmployeeFactory::HOURLY, EmployeeFactory::COMMISSION]);
            $table->enum('payment_method', [PaymentMethodFactory::DIRECT_METHOD, PaymentMethodFactory::HOLD_METHOD, PaymentMethodFactory::MAIL_METHOD]);
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
