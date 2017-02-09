<?php

namespace Payroll\Tests\Unit\Transaction;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Contract\Paycheck;
use Payroll\Employee;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Tests\TestCase;
use Payroll\TimeCard;
use Payroll\Transaction\PayDay;

class PayDayTest extends TestCase
{
    use DatabaseTransactions;

    public function testPaySalariedEmployee()
    {
        $employee = factory(Employee::class)->create(['type' => EmployeeFactory::SALARIED]);
        $payDate = '2017-01-31';
        $payDay = new PayDay($payDate);
        $payDay->execute();
        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNotNull($payCheck);

        $this->assertEquals($payDate, $payCheck->getDate());
        $this->assertEquals($employee->getSalary(), $payCheck->getNetPay());
        $this->assertEquals('HOLD', $payCheck->getType());
    }

    public function testPaySalariedEmployeeOnWrongDate()
    {
        $employee = factory(Employee::class)->create(['type' => EmployeeFactory::SALARIED]);
        $payDate = '2017-01-27';

        $payDay = new PayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }

    public function testPayHourlyEmployeeNoTimeCard()
    {
        $employee = factory(Employee::class)->create(['type' => EmployeeFactory::HOURLY]);
        $payDate = '2017-02-03'; // Friday
        $payDay = new PayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 0);
    }

    public function testPayHourlyEmployeeOneTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::HOURLY,
            'hourly_rate' => 12,
        ]);

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => '2017-02-01',
            'hours' => 2
        ]);

        $payDate = '2017-02-03'; // Friday
        $payDay = new PayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 24);
    }

    public function testPayHourlyEmployeeMoreTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::HOURLY,
            'hourly_rate' => 12,
        ]);

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => '2017-01-31',
            'hours' => 4
        ]);

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => '2017-02-01',
            'hours' => 2
        ]);

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => '2017-02-02',
            'hours' => 8
        ]);

        $payDate = '2017-02-03'; // Friday
        $payDay = new PayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 168);
    }

    public function testPayHourlyEmployeeOvertimeOneTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::HOURLY,
            'hourly_rate' => 12,
        ]);

        $payDate = '2017-02-03'; // Friday

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => $payDate,
            'hours' => 9
        ]);

        $payDay = new PayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, (8 * 12 + (1 * (12 * 1.5))));
    }

    public function testPayHourlyEmployeeOnWrongDate()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::HOURLY,
            'hourly_rate' => 12,
        ]);

        $payDate = '2017-02-01'; // Not friday

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => $payDate,
            'hours' => 9
        ]);

        $payDay = new PayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }
    
    public function testPayHourlyEmployeeWithTimeCardsSpanningTwoPayPeriods()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::HOURLY,
            'hourly_rate' => 12,
        ]);

        $payDate = '2017-02-10'; // Friday
        $dateInPreviousPeriod = '2017-02-02';

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => $payDate,
            'hours' => 4
        ]);

        factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => $dateInPreviousPeriod,
            'hours' => 5
        ]);

        $payDay = new PayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, (4 * 12));
    }

    protected function verifyHourlyPayCheck(Paycheck $payCheck, $payDate, $netPay)
    {
        $this->assertNotNull($payCheck);
        $this->assertEquals($payDate, $payCheck->getDate());
        $this->assertEquals($netPay, $payCheck->getNetPay());
        $this->assertEquals('HOLD', $payCheck->getType());
    }
}