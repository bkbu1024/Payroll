<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Tests\TestCase;

abstract class AbstractChangeEmployeeTestCase extends TestCase
{
    use DatabaseTransactions;

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

    abstract protected function setEmployee();

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