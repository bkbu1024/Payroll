<?php

namespace Payroll\Tests\Integration\Transaction;

use Payroll\Factory\Model\Employee;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Factory\Transaction\PayDay as PayDayFactory;

class PayDaySalariedTest extends AbstractPayDayTestCase
{
    public function testPayOneSalariedEmployee()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200,
        ]);

        $employee = $transaction->execute();

        $payDate = '2017-01-31';
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();
        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNotNull($payCheck);

        $this->assertEquals($payDate, $payCheck->getDate());
        $this->assertEquals($employee->getSalary(), $payCheck->getNetPay());
        $this->assertEquals('HOLD', $payCheck->getType());
    }

    public function testPayMoreSalariedEmployee()
    {
        $employees = $this->getEmployees(5, Employee::SALARIED);

        $payDate = '2017-01-31';
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payChecks = [];
        foreach ($employees as $employee) {
            $payChecks[$employee->getId()] = $payDay->getPayCheck($employee->getId());
        }

        $this->verifyMonthlyPaychecks($employees, $payChecks, $payDate);
    }

    public function testPayOneSalariedEmployeeOnWrongDate()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200,
        ]);

        $employee = $transaction->execute();
        $payDate = '2017-01-27';

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }

    public function testPayMoreSalariedEmployeeOnWrongDate()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200,
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2500,
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $payDate = '2017-01-27';

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->assertNull($payCheck1);
    }
}
