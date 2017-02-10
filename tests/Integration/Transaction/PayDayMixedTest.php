<?php

namespace Payroll\Tests\Integration\Transaction;

use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Factory\Transaction\PayDay as PayDayFactory;
use Payroll\Factory\Transaction\Add\SalesReceipt as AddSalesReceiptFactory;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;

class PayDayMixedTest extends AbstractPayDayTestCase
{
    public function testPayEmployeesAtSecondFriday()
    {
        $payDate = '2017-02-10';
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 10
        ]);

        $employeeCommissioned = $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employeeCommissioned, $payDate, 1320);
        $transaction->execute();

        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200,
        ]);

        $employeeSalaried = $transaction->execute();

        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employeeHourly = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employeeHourly, $payDate, 2);
        $transaction->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheckSalaried = $payDay->getPayCheck($employeeSalaried->getId());
        $this->assertNull($payCheckSalaried);

        $payCheckCommissioned = $payDay->getPayCheck($employeeCommissioned->getId());
        $this->verifyPayCheck($payCheckCommissioned, $payDate, 1200 + 132);

        $payCheckHourly = $payDay->getPayCheck($employeeHourly->getId());
        $this->verifyPayCheck($payCheckHourly, $payDate, 30);
    }

    public function testPayEmployeesAtEndOfMonth()
    {
        $payDate = '2017-02-28';
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 10
        ]);

        $employeeCommissioned = $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employeeCommissioned, $payDate, 1320);
        $transaction->execute();

        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200,
        ]);

        $employeeSalaried = $transaction->execute();

        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employeeHourly = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employeeHourly, $payDate, 2);
        $transaction->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheckSalaried = $payDay->getPayCheck($employeeSalaried->getId());
        $this->verifyPayCheck($payCheckSalaried, $payDate, 2200);

        $payCheckCommissioned = $payDay->getPayCheck($employeeCommissioned->getId());
        $this->assertNull($payCheckCommissioned);

        $payCheckHourly = $payDay->getPayCheck($employeeHourly->getId());
        $this->assertNull($payCheckHourly);
    }

    public function testPayEmployeesAtFirstFriday()
    {
        $payDate = '2017-02-03';
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 10
        ]);

        $employeeCommissioned = $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employeeCommissioned, $payDate, 1320);
        $transaction->execute();

        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200,
        ]);

        $employeeSalaried = $transaction->execute();

        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employeeHourly = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employeeHourly, $payDate, 2);
        $transaction->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheckSalaried = $payDay->getPayCheck($employeeSalaried->getId());
        $this->assertNull($payCheckSalaried);

        $payCheckCommissioned = $payDay->getPayCheck($employeeCommissioned->getId());
        $this->assertNull($payCheckCommissioned);

        $payCheckHourly = $payDay->getPayCheck($employeeHourly->getId());
        $this->verifyPayCheck($payCheckHourly, $payDate, 30);
    }
}
