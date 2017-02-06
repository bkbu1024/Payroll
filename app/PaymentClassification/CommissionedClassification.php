<?php

namespace Payroll\PaymentClassification;

use Illuminate\Database\Eloquent\Collection;
use Payroll\Contract\SalesReceipt;

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
     * @return float
     */
    public function calculatePay()
    {
        return 0;
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