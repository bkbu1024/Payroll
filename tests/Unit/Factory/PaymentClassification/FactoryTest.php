<?php

namespace Tests\Unit\Factory\PaymentClassification;

use Exception;
use Payroll\Employee;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\Factory\PaymentClassification\Factory;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\Tests\TestCase;

class FactoryTest extends TestCase
{
    public function testCreateClassificationBySalariedData()
    {
        $data = ['salary' => 2400];

        $classification = Factory::createClassificationByData($data);
        $this->assertTrue($classification instanceof SalariedClassification);
    }

    public function testCreateClassificationByCommissionData()
    {
        $data = ['salary' => 1500, 'commissionRate' => 10];

        $classification = Factory::createClassificationByData($data);
        $this->assertTrue($classification instanceof CommissionedClassification);
    }

    public function testCreateClassificationByHourlyData()
    {
        $data = ['hourlyRate' => 23];

        $classification = Factory::createClassificationByData($data);
        $this->assertTrue($classification instanceof HourlyClassification);
    }

    public function testCreateClassificationByInvalidData()
    {
        $data = ['invalid' => 23];

        try {
            Factory::createClassificationByData($data);
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Never should reach here', $ex->getMessage());
        }
    }

    public function testCreateClassificationBySalariedEmployee()
    {
        $employee = factory(Employee::class)->create(['payment_classification' => EmployeeFactory::SALARIED]);

        $classification = Factory::createClassificationByEmployee($employee);
        $this->assertTrue($classification instanceof SalariedClassification);
    }

    public function testCreateClassificationByCommissionedEmployee()
    {
        $employee = factory(Employee::class)->create(['payment_classification' => EmployeeFactory::COMMISSION]);

        $classification = Factory::createClassificationByEmployee($employee);
        $this->assertTrue($classification instanceof CommissionedClassification);
    }

    public function testCreateClassificationByHourlyEmployee()
    {
        $employee = factory(Employee::class)->create(['payment_classification' => EmployeeFactory::HOURLY]);

        $classification = Factory::createClassificationByEmployee($employee);
        $this->assertTrue($classification instanceof HourlyClassification);
    }

    public function testCreateClassificationByInvalidEmployee()
    {
        $employee = new Employee;

        try {
            Factory::createClassificationByEmployee($employee);
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Never should reach here', $ex->getMessage());
        }
    }
}