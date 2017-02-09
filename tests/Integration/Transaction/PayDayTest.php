<?php

namespace Payroll\Tests\Integration\Transaction;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Contract\Paycheck;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;
use Payroll\Factory\Transaction\PayDay as PayDayFactory;
use Payroll\Tests\TestCase;

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
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200
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

    public function testPaySalariedEmployeeOnWrongDate()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200
        ]);

        $employee = $transaction->execute();
        $payDate = '2017-01-27';

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }

    public function testPayHourlyEmployeeNoTimeCard()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employee = $transaction->execute();
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
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, '2017-02-01', 2);
        $transaction->execute();

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
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, '2017-01-31', 4);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-01', 2);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-02', 8);
        $transaction->execute();

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
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, $payDate, 9);
        $transaction->execute();

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
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, $payDate, 9);
        $transaction->execute();

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
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, $payDate, 4);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, $dateInPreviousPeriod, 5);
        $transaction->execute();

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