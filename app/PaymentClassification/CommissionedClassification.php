<?php

namespace Payroll\PaymentClassification;

use Payroll\Contract\Paycheck;
use Payroll\Contract\SalesReceipt;
use Illuminate\Database\Eloquent\Collection;

class CommissionedClassification extends PaymentClassification
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
     * @return float
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * @return float
     */
    public function getCommissionRate()
    {
        return $this->commissionRate;
    }

    /**
     * CommissionedClassification constructor.
     * @param float $salary
     * @param float $commissionRate
     */
    public function __construct($salary, $commissionRate)
    {
        $this->salary = $salary;
        $this->commissionRate = $commissionRate;
    }

    /**
     * @param Paycheck $paycheck
     * @return float
     */
    public function calculatePay(Paycheck $paycheck)
    {
        $salesReceipts = $this->employee->getSalesReceipts();
        $netPay = $this->employee->getSalary();


        foreach ($salesReceipts as $salesReceipt) {
            /**
             * @var SalesReceipt $salesReceipt
             */
            if ($salesReceipt->isInPayPeriod($paycheck->getDate())) {
                $netPay += $this->calculatePayForSalesReceipt($salesReceipt);
            }
        }

        return $netPay;
    }

    /**
     * @param SalesReceipt $salesReceipt
     * @return float
     */
    protected function calculatePayForSalesReceipt(SalesReceipt $salesReceipt)
    {
        $amount = $salesReceipt->getAmount();

        return $amount * ($this->employee->getCommissionRate() / 100);
    }

    /**
     * @param SalesReceipt $salesReceipt
     */
    public function addSalesReceipt(SalesReceipt $salesReceipt)
    {
        $salesReceipt->setEmployeeId($this->employee->getId());
        $this->employee->addSalesReceipt($salesReceipt);
    }

    /**
     * @param $date
     * @return SalesReceipt
     */
    public function getSalesReceipt($date)
    {
        return $this->employee->getSalesReceiptBy($date);
    }

    /**
     * @return Collection
     */
    public function getSalesReceipts()
    {
        return $this->employee->getSalesReceipts();
    }
}