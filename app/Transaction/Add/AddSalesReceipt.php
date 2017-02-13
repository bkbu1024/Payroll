<?php

namespace Payroll\Transaction\Add;

use DateTime;
use Exception;
use Payroll\Transaction\Transaction;
use Payroll\Contract\Employee;
use Payroll\Contract\SalesReceipt;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\Model\SalesReceipt as SalesReceiptFactory;

class AddSalesReceipt implements Transaction
{
    /**
     * @var DateTime
     */
    private $date;
    /**
     * @var float
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
     * @throws Exception
     */
    public function execute()
    {
        if ($this->employee->getPaymentClassification()->getType() != EmployeeFactory::COMMISSION) {
            throw new Exception('Tried to add sales receipt to non-commissioned employee');
        }

        $salesReceipt = SalesReceiptFactory::createSalesReceipt([
            'date' => $this->date,
            'amount' => $this->amount]);

        $paymentClassification = $this->employee->getPaymentClassification();
        $paymentClassification->addSalesReceipt($salesReceipt);

        return $salesReceipt;
    }
}
