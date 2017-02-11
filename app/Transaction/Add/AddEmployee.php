<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\Transaction\Transaction;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\PaymentMethod\Factory as MethodFactory;


abstract class AddEmployee implements Transaction
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $address;

    /**
     * @param $name
     * @param $address
     */
    public function __construct($name, $address)
    {
        $this->name = $name;
        $this->address = $address;
    }

    /**
     * @return PaymentClassification
     */
    abstract protected function getPaymentClassification();

    /**
     * @return PaymentSchedule
     */
    abstract protected function getPaymentSchedule();

    /**
     * @return Employee
     */
    public function execute()
    {
        $classification = $this->getPaymentClassification();
        $schedule = $this->getPaymentSchedule();


        $employee = $this->createEmployee();
        $employee->setPaymentClassification($classification);
        $employee->setPaymentSchedule($schedule);

        $classification->setEmployee($employee);

        return $employee;
    }

    /**
     * @return Employee
     */
    protected function createEmployee()
    {
        $employee = EmployeeFactory::createEmployee();
        $employee->setName($this->name);
        $employee->setAddress($this->address);

        $method = MethodFactory::createByData();
        $employee->setPaymentMethod($method);

        return $employee;
    }
}
