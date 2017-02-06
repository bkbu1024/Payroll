<?php

namespace Payroll\Transaction\Add;

use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\PaymentClassification\HourlyClassification;

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
     * @param $hourlyRate
     */
    public function __construct($name, $address, $hourlyRate)
    {
        parent::__construct($name, $address);
        $this->hourlyRate = $hourlyRate;
    }

    /**
     * @return HourlyClassification
     */
    protected function getPaymentClassification()
    {
        return new HourlyClassification($this->hourlyRate);
    }

    /**
     * @return WeeklySchedule
     */
    protected function getPaymentSchedule()
    {
        return new WeeklySchedule;
    }

    /**
     * @return \Payroll\Employee
     */
    protected function createEmployee()
    {
        $employee = parent::createEmployee();
        $employee->setHourlyRate($this->hourlyRate);
        $employee->setType(self::HOURLY);
        $employee->save();

        return $employee;
    }
}