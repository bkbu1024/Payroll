<?php

namespace Payroll\Transaction;

use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentClassification\CommissionedClassification;

class AddCommissionedEmployee extends AddEmployee
{
    /**
     * @var float
     */
    private $salary;

    /**
     * @var float
     */
    private $commission;

    /**
     * AddCommissionedEmployee constructor.
     * @param $name
     * @param $address
     * @param $hourlyRate
     * @param $commission
     */
    public function __construct($name, $address, $hourlyRate, $commission)
    {
        parent::__construct($name, $address);
        $this->salary = $hourlyRate;
        $this->commission = $commission;
    }

    /**
     * @return CommissionedClassification
     */
    protected function getPaymentClassification()
    {
        return new CommissionedClassification($this->salary, $this->commission);
    }

    /**
     * @return BiweeklySchedule
     */
    protected function getPaymentSchedule()
    {
        return new BiweeklySchedule;
    }

    /**
     * @return \Payroll\Employee
     */
    protected function createEmployee()
    {
        $employee = parent::createEmployee();
        $employee->salary = $this->salary;
        $employee->commission = $this->commission;
        $employee->type = self::COMMISSION;
        $employee->save();

        return $employee;
    }
}