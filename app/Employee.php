<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Model;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\PaymentMethod\PaymentMethod;

class Employee extends Model
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
    public function setPaymentClassification($paymentClassification)
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
    public function setPaymentSchedule($paymentSchedule)
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
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function timeCards()
    {
        return $this->hasMany(TimeCard::class);
    }

    /**
     * @param TimeCard $timeCard
     */
    public function addTimeCard(TimeCard $timeCard)
    {
        $timeCard->employee_id = $this->employee_id;
        $this->timeCards()->save($timeCard);
    }

    /**
     * @param string $date
     * @return mixed
     */
    public function getTimeCard($date)
    {
        return $this->timeCards()->where('date', $date)->get()->first();
    }
}
