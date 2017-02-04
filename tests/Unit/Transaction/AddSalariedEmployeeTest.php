<?php

namespace Payroll\Tests\Unit\Transaction;

use Payroll\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\Transaction\AddEmployee;
use Payroll\Transaction\AddSalariedEmployee;

class AddSalariedEmployeeTest extends TestCase
{
    /**
     * @covers AddSalariedEmployee::execute()
     */
    public function testExecute()
    {
        $faker = Factory::create();
        $name = $faker->name;
        $salary = $faker->randomFloat(2, 1250, 3750);

        $transaction = new AddSalariedEmployee(
            $name,
            $faker->address,
            $salary);

        $employee = $transaction->execute();
        $this->assertEquals($name, $employee->name);
        
        $this->assertTrue($employee->getPaymentClassification() instanceof SalariedClassification);
        $this->assertEquals($salary, $employee->salary);
        $this->assertEquals(AddEmployee::SALARIED, $employee->type);

        $this->assertTrue($employee->getPaymentSchedule() instanceof MonthlySchedule);
        $this->assertTrue($employee->getPaymentMethod() instanceof HoldMethod);
    }
}
