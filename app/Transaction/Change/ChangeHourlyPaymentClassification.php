<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Add\AddEmployee;

class ChangeHourlyPaymentClassification extends ChangePaymentClassification
{
    /**
     * @var float
     */
    private $hourlyRate;

    /**
     * ChangeHourlyPaymentClassification constructor.
     *
     * @param Employee $employee
     * @param $salary
     */
    public function __construct(Employee $employee, $salary)
    {
        parent::__construct($employee);
        $this->hourlyRate = $salary;
    }

    /**
     * @return HourlyClassification
     */
    protected function getPaymentClassification()
    {
        $paymentClassification = new HourlyClassification($this->hourlyRate);
        $paymentClassification->setEmployee($this->employee);

        return $paymentClassification;
    }

    /**
     * @return WeeklySchedule
     */
    protected function getPaymentSchedule()
    {
        return new WeeklySchedule;
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return AddEmployee::HOURLY;
    }
}
