<?php

namespace Tests\Unit\Factory\PaymentSchedule;

use Exception;
use Payroll\Employee;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Factory\PaymentSchedule\Factory;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Tests\TestCase;

class FactoryTest extends TestCase
{
    public function testCreateScheduleBySalariedData()
    {
        $data = ['salary' => 2400];

        $classification = Factory::createScheduleByData($data);
        $this->assertTrue($classification instanceof MonthlySchedule);
    }

    public function testCreateScheduleByCommissionData()
    {
        $data = ['salary' => 1500, 'commissionRate' => 10];

        $classification = Factory::createScheduleByData($data);
        $this->assertTrue($classification instanceof BiweeklySchedule);
    }

    public function testCreateScheduleByHourlyData()
    {
        $data = ['hourlyRate' => 23];

        $classification = Factory::createScheduleByData($data);
        $this->assertTrue($classification instanceof WeeklySchedule);
    }

    public function testCreateScheduleByInvalidData()
    {
        $data = ['invalid' => 23];

        try {
            Factory::createScheduleByData($data);
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Never should reach here', $ex->getMessage());
        }
    }

    public function testCreateScheduleBySalariedEmployee()
    {
        $employee = factory(Employee::class)->create(['payment_classification' => EmployeeFactory::SALARIED]);

        $classification = Factory::createScheduleByEmployee($employee);
        $this->assertTrue($classification instanceof MonthlySchedule);
    }

    public function testCreateScheduleByCommissionedEmployee()
    {
        $employee = factory(Employee::class)->create(['payment_classification' => EmployeeFactory::COMMISSION]);

        $classification = Factory::createScheduleByEmployee($employee);
        $this->assertTrue($classification instanceof BiweeklySchedule);
    }

    public function testCreateScheduleByHourlyEmployee()
    {
        $employee = factory(Employee::class)->create(['payment_classification' => EmployeeFactory::HOURLY]);

        $classification = Factory::createScheduleByEmployee($employee);
        $this->assertTrue($classification instanceof WeeklySchedule);
    }

    public function testCreateScheduleByInvalidEmployee()
    {
        $employee = new Employee;

        try {
            Factory::createScheduleByEmployee($employee);
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Never should reach here', $ex->getMessage());
        }
    }
}