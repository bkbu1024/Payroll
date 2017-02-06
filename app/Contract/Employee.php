<?php

namespace Payroll\Contract;

use Illuminate\Database\Eloquent\Collection;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\SalesReceipt;
use Payroll\TimeCard;

interface Employee
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

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
     * @param int $id
     */
    public function setId($id);

    /**
     * @param string $name
     */
    public function setName($name);

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

    /**
     * @param SalesReceipt $salesReceipt
     * @return void
     */
    public function addSalesReceipt(SalesReceipt $salesReceipt);

    /**
     * @param string $date
     * @return mixed
     */
    public function getSalesReceiptBy($date);

    /**
     * @return Collection
     */
    public function getSalesReceipts();

    /**
     * @param TimeCard $timeCard
     * @return void
     */
    public function addTimeCard(TimeCard $timeCard);

    /**
     * @param string $date
     * @return TimeCard
     */
    public function getTimeCardBy($date);

    /**
     * @return Collection
     */
    public function getTimeCards();
}