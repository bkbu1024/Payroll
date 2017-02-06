<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\PaymentMethod\PaymentMethod;

class Employee extends Model implements Contract\Employee
{
    /**
     * @var PaymentClassification
     */
    protected $paymentClassification;

    /**
     * @var PaymentSchedule
     */
    protected $paymentSchedule;

    /**
     * @var PaymentMethod
     */
    protected $paymentMethod;



    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSalary()
    {
        return $this->salary;
    }

    public function getHourlyRate()
    {
        return $this->hourly_rate;
    }

    public function getCommissionRate()
    {
        return $this->commission_rate;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function setSalary($salary)
    {
        $this->salary = $salary;
    }

    public function setHourlyRate($hourlyRate)
    {
        $this->hourly_rate = $hourlyRate;
    }

    public function setCommissionRate($commissionRate)
    {
        $this->commission_rate = $commissionRate;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    // --------------- END getters and setters

    /**
     * @return PaymentClassification
     */
    public function getPaymentClassification()
    {
        return $this->paymentClassification;
    }

    /**
     * @param PaymentClassification $paymentClassification
     */
    public function setPaymentClassification(PaymentClassification $paymentClassification)
    {
        $this->paymentClassification = $paymentClassification;
    }

    /**
     * @return PaymentSchedule
     */
    public function getPaymentSchedule()
    {
        return $this->paymentSchedule;
    }

    /**
     * @param PaymentSchedule $paymentSchedule
     */
    public function setPaymentSchedule(PaymentSchedule $paymentSchedule)
    {
        $this->paymentSchedule = $paymentSchedule;
    }

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param PaymentMethod $paymentMethod
     */
    public function setPaymentMethod(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return Collection
     */
    public function getTimeCards()
    {
        return $this->timeCards()->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function timeCards()
    {
        return $this->hasMany(TimeCard::class);
    }

    /**
     * @return Collection
     */
    public function getSalesReceipts()
    {
        return $this->salesReceipts()->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function salesReceipts()
    {
        return $this->hasMany(\Payroll\SalesReceipt::class);
    }

    /**
     * @param SalesReceipt $salesReceipt
     */
    public function addSalesReceipt(SalesReceipt $salesReceipt)
    {
        $this->salesReceipts()->save($salesReceipt);
    }

    /**
     * @param string $date
     * @return SalesReceipt
     */
    public function getSalesReceiptBy($date)
    {
        return $this->salesReceipts()->where('date', $date)->get()->first();
    }

    /**
     * @param TimeCard $timeCard
     */
    public function addTimeCard(TimeCard $timeCard)
    {
        $this->timeCards()->save($timeCard);
    }

    /**
     * @param string $date
     * @return TimeCard
     */
    public function getTimeCardBy($date)
    {
        return $this->timeCards()->where('date', $date)->get()->first();
    }
}
