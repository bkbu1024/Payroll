<?php

namespace Tests\Unit\Factory\Model;

use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Tests\TestCase;

class EmployeeTest extends TestCase
{
    public function testCreateEmployee()
    {
        $employee = EmployeeFactory::createEmployee();
        $this->assertTrue($employee instanceof EmployeeContract);
    }
}