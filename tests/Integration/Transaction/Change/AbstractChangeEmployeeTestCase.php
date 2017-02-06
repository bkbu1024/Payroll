<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Tests\TestCase;

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

    abstract protected function setEmployee();

    abstract protected function assertTypeSpecificData();

    abstract protected function change();

    public function testExecute()
    {
        $this->setEmployee();
        $this->change();
        $this->assertTypeSpecificData();
    }
}