<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\Factory as ClassificationFactory;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\Factory as ScheduleFactory;
use Payroll\PaymentSchedule\PaymentSchedule;

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
        $employee->setType(self::HOURLY);
        $employee->save();

        return $employee;
    }
}