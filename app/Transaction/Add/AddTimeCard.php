<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\Factory\Employee as EmployeeFactory;
use Payroll\Factory\TimeCard as TimeCardFactory;
use Payroll\Contract\TimeCard;
use Payroll\Transaction\Transaction;

class AddTimeCard implements Transaction
{
    /**
     * @var \DateTime
     */
    private $date;
    /**
     * @var float
     */
    private $hours;
    /**
     * @var Employee
     */
    private $employee;

    /**
     * AddTimeCard constructor.
     *
     * @param $date
     * @param $hours
     * @param Employee $employee
     */
    public function __construct($date, $hours, Employee $employee)
    {
        $this->date = $date;
        $this->hours = $hours;
        $this->employee = $employee;
    }

    /**
     * @return TimeCard
     * @throws \Exception
     */
    public function execute()
    {
        if ($this->employee->getType() != EmployeeFactory::HOURLY) {
            throw new \Exception('Tried to add time card to non-hourly employee');
        }

        $timeCard = TimeCardFactory::createTimeCard([
            'date' => $this->date,
            'hours' => $this->hours]);

        $paymentClassification = $this->employee->getPaymentClassification();
        $paymentClassification->addTimeCard($timeCard);

        return $timeCard;
    }
}
