<?php

namespace Payroll\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\Contract\TimeCard;
use Payroll\Transaction\Transaction;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\Model\TimeCard as TimeCardFactory;

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
        if ($this->employee->getPaymentClassification()->getType() != EmployeeFactory::HOURLY) {
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
