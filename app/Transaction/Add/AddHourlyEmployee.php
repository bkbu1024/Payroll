<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Employee as EmployeeFactory;
use Payroll\PaymentClassification\Factory as ClassificationFactory;
use Payroll\PaymentSchedule\Factory as ScheduleFactory;

class AddHourlyEmployee extends AddEmployee
{
    /**
     * @var
     */
    private $hourlyRate;

    /**
     * AddSalariedEmployee constructor.
     * @param $name
     * @param $address
     * @param $salary
     */
    public function __construct($name, $address, $salary)
    {
        parent::__construct($name, $address);
        $this->hourlyRate = $salary;
    }

    /**
     * @return PaymentClassification
     */
    protected function getPaymentClassification()
    {
        return ClassificationFactory::createClassificationByData([
            'hourlyRate' => $this->hourlyRate]);
    }

    /**
     * @return PaymentSchedule
     */
    protected function getPaymentSchedule()
    {
        return ScheduleFactory::createScheduleByData([
            'hourlyRate' => $this->hourlyRate
        ]);
    }

    /**
     * @return Employee
     */
    protected function createEmployee()
    {
        $employee = parent::createEmployee();
        $employee->setHourlyRate($this->hourlyRate);
        $employee->setType(EmployeeFactory::HOURLY);
        $employee->save();

        return $employee;
    }
}