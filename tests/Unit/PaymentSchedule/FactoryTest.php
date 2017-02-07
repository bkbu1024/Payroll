<?php

namespace Unit\PaymentSchedule;

use Exception;
use Payroll\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentSchedule\Factory;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentClassification\SalariedClassification;
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
        $employee = new Employee;
        $employee->salary = 2400;

        $classification = Factory::createScheduleByEmployee($employee);
        $this->assertTrue($classification instanceof MonthlySchedule);
    }

    public function testCreateScheduleByCommissionedEmployee()
    {
        $employee = new Employee;
        $employee->salary = 1500;
        $employee->commission_rate = 10;

        $classification = Factory::createScheduleByEmployee($employee);
        $this->assertTrue($classification instanceof BiweeklySchedule);
    }

    public function testCreateScheduleByHourlyEmployee()
    {
        $employee = new Employee;
        $employee->hourly_rate = 23;

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