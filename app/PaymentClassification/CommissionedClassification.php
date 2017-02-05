<?php

namespace Payroll\PaymentClassification;

use Payroll\SalesReceipt;

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
        $salesReceipt->employee_id = $this->employee->id;
        $this->employee->salesReceipts()->save($salesReceipt);
    }

    /**
     * @param $date
     * @return mixed
     */
    public function getSalesReceipt($date)
    {
        return $this->employee->salesReceipts()->where('date', $date)->get()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salesReceipts()
    {
        return $this->employee->salesReceipts();
    }
}