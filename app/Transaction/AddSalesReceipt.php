<?php

namespace Payroll\Transaction;

use Payroll\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\SalesReceipt;
use Payroll\TimeCard;

class AddSalesReceipt implements Transaction
{
    /**
     * @var
     */
    private $date;
    /**
     * @var
     */
    private $amount;
    /**
     * @var Employee
     */
    private $employee;

    /**
     * AddSalesReceipt constructor.
     * @param $date
     * @param $amount
     * @param Employee $employee
     */
    public function __construct($date, $amount, Employee $employee)
    {
        $this->date = $date;
        $this->amount = $amount;
        $this->employee = $employee;
    }

    /**
     * @return SalesReceipt
     * @throws \Exception
     */
    public function execute()
    {
        /**
         * @var CommissionedClassification $paymentClassification
         */
        $paymentClassification = $this->employee->getPaymentClassification();
        if ( ! $paymentClassification instanceof CommissionedClassification) {
            throw new \Exception('Tried to add sales receipt to non-commissioned employee');
        }

        $salesReceipt = new SalesReceipt([
            'date' => $this->date,
            'amount' => $this->amount, ]);

        $paymentClassification->addSalesReceipt($salesReceipt);

        return $salesReceipt;
    }
}
