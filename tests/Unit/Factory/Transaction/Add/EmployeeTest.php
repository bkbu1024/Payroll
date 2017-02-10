<?php

namespace Unit\Factory\Transaction\Add;

use Exception;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Add\AddSalariedEmployee;

class EmployeeTest extends TestCase
{
    protected $baseData = [];

    public function __construct()
    {
        parent::__construct();
        $this->baseData = [
            'name' => $this->faker->name,
            'address' => $this->faker->address
        ];
    }

    public function testCreateCommissioned()
    {
        $this->baseData['salary'] = 1000;
        $this->baseData['commissionRate'] = 10;

        $transaction = AddEmployeeFactory::create($this->baseData);
        $this->assertTrue($transaction instanceof AddCommissionedEmployee);
    }

    public function testCreateSalaried()
    {
        $this->baseData['salary'] = 1000;

        $transaction = AddEmployeeFactory::create($this->baseData);
        $this->assertTrue($transaction instanceof AddSalariedEmployee);
    }

    public function testCreateHourly()
    {
        $this->baseData['hourlyRate'] = 1000;

        $transaction = AddEmployeeFactory::create($this->baseData);
        $this->assertTrue($transaction instanceof AddHourlyEmployee);
    }

    public function testCreateInvalidData()
    {
        $this->baseData['invalid'] = 1000;

        try {
            AddEmployeeFactory::create($this->baseData);
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Never should reach here', $ex->getMessage());
        }
    }
}