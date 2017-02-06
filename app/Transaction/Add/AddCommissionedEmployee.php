<?php

namespace Payroll\Transaction\Add;

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
    private $commissionRate;

    /**
     * AddCommissionedEmployee constructor.
     * @param $name
     * @param $address
     * @param $hourlyRate
     * @param $commissionRate
     */
    public function __construct($name, $address, $hourlyRate, $commissionRate)
    {
        parent::__construct($name, $address);
        $this->salary = $hourlyRate;
        $this->commissionRate = $commissionRate;
    }

    /**
     * @return CommissionedClassification
     */
    protected function getPaymentClassification()
    {
        return new CommissionedClassification($this->salary, $this->commissionRate);
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
        $employee->setSalary($this->salary);
        $employee->setCommissionRate($this->commissionRate);
        $employee->setType(self::COMMISSION);
        $employee->save();

        return $employee;
    }
}