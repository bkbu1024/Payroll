<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\PaymentClassification\Factory as ClassificationFactory;
use Payroll\Factory\PaymentSchedule\Factory as ScheduleFactory;

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
     * @param $salary
     * @param $commissionRate
     */
    public function __construct($name, $address, $salary, $commissionRate)
    {
        parent::__construct($name, $address);
        $this->salary = $salary;
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