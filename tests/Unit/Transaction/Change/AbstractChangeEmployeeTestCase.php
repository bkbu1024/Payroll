<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\Employee;
use Payroll\Tests\TestCase;
use Faker\Factory;
use Payroll\PaymentMethod\HoldMethod;
use Illuminate\Foundation\Testing\DatabaseTransactions;

abstract class AbstractChangeEmployeeTestCase extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var \Faker\Generator
     */
    protected $faker = null;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var Employee
     */
    protected $employee = null;

    /**
     * @var Employee
     */
    protected $changedEmployee = null;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    protected function setEmployee()
    {
        $employee = new Employee;
        $employee->setName($this->faker->name);
        $employee->setAddress($this->faker->address);
        $employee->setPaymentMethod(new HoldMethod);

        $this->employee = $employee;
    }

    abstract protected function assertTypeSpecificData();

    abstract protected function change();

    public function testExecute()
    {
        $this->setEmployee();
        $this->employee->save();
        $this->change();
        $this->assertDatabaseHas('employees', $this->changedEmployee->toArray());
        $this->assertTypeSpecificData();
    }
}
