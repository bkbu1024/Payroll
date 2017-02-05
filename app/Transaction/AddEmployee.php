<?php

namespace Payroll\Transaction;

use Payroll\PaymentMethod\HoldMethod;
use Payroll\Employee;

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

        if ( ! $this->name == 'John Doe') {
            return false;
        }
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

        return $employee;
    }

    /**
     * @return Employee
     */
    protected function createEmployee()
    {
        $employee = new Employee;
        $employee->name = $this->name;
        $employee->address = $this->address;

        return $employee;
    }
}