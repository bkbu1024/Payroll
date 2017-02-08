<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Employee as EmployeeFactory;
use Payroll\PaymentClassification\Factory as ClassificationFactory;
use Payroll\PaymentSchedule\Factory as ScheduleFactory;

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
     * @return PaymentClassification
     */
    protected function getPaymentClassification()
    {
        return ClassificationFactory::createClassificationByData([
            'salary' => $this->salary,
            'commissionRate' => $this->commissionRate]);
    }

    /**
     * @return PaymentSchedule
     */
    protected function getPaymentSchedule()
    {
        return ScheduleFactory::createScheduleByData([
            'salary' => $this->salary,
            'commissionRate' => $this->commissionRate]);
    }

    /**
     * @return Employee
     */
    protected function createEmployee()
    {
        $employee = parent::createEmployee();
        $employee->setSalary($this->salary);
        $employee->setCommissionRate($this->commissionRate);
        $employee->setType(EmployeeFactory::COMMISSION);
        $employee->save();

        return $employee;
    }
}