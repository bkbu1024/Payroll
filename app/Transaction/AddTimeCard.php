<?php

namespace Payroll\Transaction;

use Payroll\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\TimeCard;

class AddTimeCard implements Transaction
{
    /**
     * @var
     */
    private $date;
    /**
     * @var
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
        $paymentClassification = $this->employee->getPaymentClassification();
        if ( ! $paymentClassification instanceof HourlyClassification) {
            throw new \Exception('Tried to add time card to non-hourly employee');
        }

        $timeCard = new TimeCard([
            'date' => $this->date,
            'hours' => $this->hours, ]);

        $paymentClassification->addTimeCard($timeCard);

        return $timeCard;
    }
}
