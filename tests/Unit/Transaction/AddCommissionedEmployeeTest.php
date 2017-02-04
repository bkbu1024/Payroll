<?php

namespace Payroll\Tests\Unit\Transaction;

use Payroll\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\Transaction\AddCommissionedEmployee;
use Payroll\Transaction\AddEmployee;

class AddCommissionedEmployeeTest extends TestCase
{
    /**
     * @covers AddCommissionedEmployee::execute()
     */
    public function testExecute()
    {
        $faker = Factory::create();
        $name = $faker->name;
        $salary = $faker->randomFloat(2, 750, 2250);
        $commission = $faker->randomFloat(2, 75, 250);

        $transaction = new AddCommissionedEmployee(
            $name,
            $faker->address,
            $salary,
            $commission);

        $employee = $transaction->execute();
        $this->assertEquals($name, $employee->name);
        
        $this->assertTrue($employee->getPaymentClassification() instanceof CommissionedClassification);
        $this->assertEquals($salary, $employee->salary);
        $this->assertEquals($commission, $employee->commission);
        $this->assertEquals(AddEmployee::COMMISSION, $employee->type);

        $this->assertTrue($employee->getPaymentSchedule() instanceof BiweeklySchedule);
        $this->assertTrue($employee->getPaymentMethod() instanceof HoldMethod);
    }
}
