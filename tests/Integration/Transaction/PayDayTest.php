<?php

namespace Payroll\Tests\Integration\Transaction;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Contract\Paycheck;
use Payroll\Factory\PayDay as PayDayFactory;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Add\AddSalariedEmployee;
use Payroll\Transaction\Add\AddTimeCard;

class PayDayTest extends TestCase
{
    use DatabaseTransactions;

    protected $faker = null;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function testPaySalariedEmployee()
    {
        $employee = (new AddSalariedEmployee($this->faker->name, $this->faker->address, 2200))->execute();
        $payDate = '2017-01-31';
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();
        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNotNull($payCheck);

        $this->assertEquals($payDate, $payCheck->getDate());
        $this->assertEquals($employee->getSalary(), $payCheck->getNetPay());
        $this->assertEquals('HOLD', $payCheck->getType());
    }

    public function testPaySalariedEmployeeOnWrongDate()
    {
        $employee = (new AddSalariedEmployee($this->faker->name, $this->faker->address, 2200))->execute();
        $payDate = '2017-01-27';

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }

    public function testPayHourlyEmployeeNoTimeCard()
    {
        $employee = (new AddHourlyEmployee($this->faker->name, $this->faker->address, 15))->execute();
        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 0);
    }

    public function testPayHourlyEmployeeOneTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = (new AddHourlyEmployee($this->faker->name, $this->faker->address, 12))->execute();
        (new AddTimeCard('2017-02-01', 2, $employee))->execute();

        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 24);
    }

    public function testPayHourlyEmployeeMoreTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = (new AddHourlyEmployee($this->faker->name, $this->faker->address, 12))->execute();
        (new AddTimeCard('2017-01-31', 4, $employee))->execute();
        (new AddTimeCard('2017-02-01', 2, $employee))->execute();
        (new AddTimeCard('2017-02-02', 8, $employee))->execute();

        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 168);
    }

    public function testPayHourlyEmployeeOvertimeOneTimeCard()
    {
        $payDate = '2017-02-03'; // Friday
        /**
         * @var EmployeeContract $employee
         */
        $employee = (new AddHourlyEmployee($this->faker->name, $this->faker->address, 12))->execute();
        (new AddTimeCard($payDate, 9, $employee))->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, (8 * 12 + (1 * (12 * 1.5))));
    }

    public function testPayHourlyEmployeeOnWrongDate()
    {
        $payDate = '2017-02-01'; // Not friday

        /**
         * @var EmployeeContract $employee
         */
        $employee = (new AddHourlyEmployee($this->faker->name, $this->faker->address, 12))->execute();
        (new AddTimeCard($payDate, 9, $employee))->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }
    
    public function testPayHourlyEmployeeWithTimeCardsSpanningTwoPayPeriods()
    {
        $payDate = '2017-02-10'; // Friday
        $dateInPreviousPeriod = '2017-02-02';

        /**
         * @var EmployeeContract $employee
         */
        $employee = (new AddHourlyEmployee($this->faker->name, $this->faker->address, 12))->execute();
        (new AddTimeCard($payDate, 4, $employee))->execute();
        (new AddTimeCard($dateInPreviousPeriod, 5, $employee))->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
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