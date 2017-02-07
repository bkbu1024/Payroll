<?php

namespace Payroll\Contract;

use Payroll\Contract\Base\Identifiable;
use Payroll\Contract\Base\Nameable;
use Payroll\Contract\Relation\HasSalesReceipts;
use Payroll\Contract\Relation\HasTimeCards;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\PaymentSchedule\PaymentSchedule;

interface Employee extends Identifiable, Nameable, HasTimeCards, HasSalesReceipts
{
    /**
     * @return string
     */
    public function getAddress();

    /**
     * @return float
     */
    public function getSalary();

    /**
     * @return float
     */
    public function getHourlyRate();

    /**
     * @return float
     */
    public function getCommissionRate();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return PaymentClassification
     */
    public function getPaymentClassification();

    /**
     * @return PaymentMethod
     */
    public function getPaymentMethod();

    /**
     * @return PaymentSchedule
     */
    public function getPaymentSchedule();

    /**
     * @param string $address
     */
    public function setAddress($address);

    /**
     * @param float $salary
     */
    public function setSalary($salary);

    /**
     * @param float $hourlyRate
     */
    public function setHourlyRate($hourlyRate);

    /**
     * @param float $commissionRate
     */
    public function setCommissionRate($commissionRate);

    /**
     * @param string $type
     */
    public function setType($type);

    /**
     * @param PaymentClassification $paymentClassification
     */
    public function setPaymentClassification(PaymentClassification $paymentClassification);

    /**
     * @param PaymentMethod $paymentMethod
     */
    public function setPaymentMethod(PaymentMethod $paymentMethod);

    /**
     * @param PaymentSchedule $paymentSchedule
     */
    public function setPaymentSchedule(PaymentSchedule $paymentSchedule);

    // ------------------------ END getters and setters
}