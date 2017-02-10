<?php

namespace Payroll\Tests\Integration\Transaction;

use Payroll\Contract\Paycheck;
use Payroll\Factory\Model\Employee;
use Payroll\Tests\TestCase;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class AbstractPayDayTestCase extends TestCase
{
    protected function verifyHourlyPayCheck(Paycheck $payCheck, $payDate, $netPay)
    {
        $this->assertNotNull($payCheck);
        $this->assertEquals($payDate, $payCheck->getDate());
        $this->assertEquals($netPay, $payCheck->getNetPay());
        $this->assertEquals('HOLD', $payCheck->getType());
    }

    protected function getEmployees($count, $type)
    {
        $employees = [];
        for ($i = 0; $i < $count; $i++) {
            if ($type == Employee::COMMISSION) {
                $transaction = AddEmployeeFactory::create([
                    'name' => $this->faker->name, 'address' => $this->faker->address,
                    'salary' => $this->faker->randomFloat(2, 2000, 3300), 'commissionRate' => $this->faker->randomFloat(2, 10, 30)
                ]);
            } elseif ($type == Employee::SALARIED) {
                $transaction = AddEmployeeFactory::create([
                    'name' => $this->faker->name, 'address' => $this->faker->address,
                    'salary' => $this->faker->randomFloat(2, 2000, 3300)
                ]);
            } elseif ($type == Employee::HOURLY) {
                $transaction = AddEmployeeFactory::create([
                    'name' => $this->faker->name, 'address' => $this->faker->address,
                    'hourlyRate' => $this->faker->randomFloat(2, 12, 35)
                ]);
            }

            $employee = $transaction->execute();
            $employees[$employee->getId()] = $employee;
        }

        return $employees;
    }

    protected function verifyMonthlyPaychecks(array $employees, array $payChecks, $payDate)
    {
        $this->assertNotEmpty($payChecks);
        foreach ($payChecks as $id => $payCheck) {
            $this->assertEquals($payDate, $payCheck->getDate());
            $this->assertEquals($employees[$id]->getSalary(), $payCheck->getNetPay());
            $this->assertEquals('HOLD', $payCheck->getType());
        }
    }

    protected function verityMoreHourlyPayChecks($employees, $payDay, $payDate, $netPays)
    {
        foreach ($employees as $employee) {
            $payCheck = $payDay->getPayCheck($employee->getId());
            $this->verifyHourlyPayCheck($payCheck, $payDate, $netPays[$employee->getId()]);
        }
    }
}