<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\PaymentClassification\Factory as ClassificationFactory;
use Payroll\Factory\PaymentSchedule\Factory as ScheduleFactory;

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
     * @param $salary
     */
    public function __construct($name, $address, $salary)
    {
        parent::__construct($name, $address);
        $this->salary = $salary;
    }

    /**
     * @return PaymentClassification
     */
    protected function getPaymentClassification()
    {
        return ClassificationFactory::createClassificationByData([
            'salary' => $this->salary
        ]);
    }

    /**
     * @return PaymentSchedule
     */
    protected function getPaymentSchedule()
    {
        return ScheduleFactory::createScheduleByData([
            'salary' => $this->salary
        ]);
    }

    /**
     * @return Employee
     */
    protected function createEmployee()
    {
        $employee = parent::createEmployee();
        $employee->setSalary($this->salary);
        $employee->setType(EmployeeFactory::SALARIED);
        $employee->save();

        return $employee;
    }
}