<?php

namespace Payroll\Transaction\Add;

use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentClassification\SalariedClassification;

class AddSalariedEmployee extends AddEmployee
{
    /**
     * @var
     */
    private $salary;

    /**
     * AddSalariedEmployee constructor.
     * @param $name
     * @param $address
     * @param $hourlyRate
     */
    public function __construct($name, $address, $hourlyRate)
    {
        parent::__construct($name, $address);
        $this->salary = $hourlyRate;
    }

    /**
     * @return SalariedClassification
     */
    protected function getPaymentClassification()
    {
        return new SalariedClassification($this->salary);
    }

    /**
     * @return MonthlySchedule
     */
    protected function getPaymentSchedule()
    {
        return new MonthlySchedule;
    }

    /**
     * @return \Payroll\Employee
     */
    protected function createEmployee()
    {
        $employee = parent::createEmployee();
        $employee->setSalary($this->salary);
        $employee->setType(self::SALARIED);
        $employee->save();

        return $employee;
    }
}