<?php

namespace Payroll\Transaction\Add;

use Payroll\PaymentMethod\HoldMethod;
use Payroll\Employee;
use Payroll\Transaction\Transaction;

abstract class AddEmployee implements Transaction
{
    const SALARIED = 'SALARIED';
    const HOURLY = 'HOURLY';
    const COMMISSION = 'COMMISSION';

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
     * @return \Payroll\PaymentClassification\PaymentClassification
     */
    abstract protected function getPaymentClassification();

    /**
     * @return \Payroll\PaymentSchedule\PaymentSchedule
     */
    abstract protected function getPaymentSchedule();

    /**
     * @return Employee
     */
    public function execute()
    {
        $classification = $this->getPaymentClassification();
        $schedule = $this->getPaymentSchedule();
        $method = new HoldMethod;

        $employee = $this->createEmployee();
        $employee->setPaymentClassification($classification);
        $employee->setPaymentSchedule($schedule);
        $employee->setPaymentMethod($method);

        $classification->setEmployee($employee);

        return $employee;
    }

    /**
     * @return Employee
     */
    protected function createEmployee()
    {
        $employee = new Employee;
        $employee->setName($this->name);
        $employee->setAddress($this->address);

        return $employee;
    }
}
