<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Payroll\Contract\Paycheck;
use Payroll\Factory\Model\Paycheck as PaycheckFactory;
use Payroll\Paycheck as PaycheckModel;
use Payroll\Factory\PaymentClassification\Factory as ClassificationFactory;
use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\Factory\PaymentSchedule\Factory as ScheduleFactory;
use Payroll\PaymentSchedule\PaymentSchedule;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\Contract\SalesReceipt;
use Payroll\Contract\TimeCard;
use Payroll\SalesReceipt as SalesReceiptModel;
use Payroll\TimeCard as TimeCardModel;

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
        if ( ! $this->paymentClassification) {
            $this->paymentClassification = ClassificationFactory::createClassificationByEmployee($this);
        }

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
        if ( ! $this->paymentSchedule) {
            $this->paymentSchedule = ScheduleFactory::createScheduleByEmployee($this);
        }

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
        if ( ! $this->paymentMethod) {
            $this->paymentMethod = new HoldMethod;
        }

        return $this->paymentMethod;
    }

    /**
     * @param PaymentMethod $paymentMethod
     */
    public function setPaymentMethod(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $this->payment_method = $paymentMethod->getType();
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
        return $this->hasMany(TimeCardModel::class);
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
        return $this->hasMany(SalesReceiptModel::class);
    }

    /**
     * @param SalesReceipt $salesReceipt
     */
    public function addSalesReceipt(SalesReceipt $salesReceipt)
    {
        $receipt = new SalesReceiptModel();
        $receipt->setEmployeeId($salesReceipt->getEmployeeId());
        $receipt->setAmount($salesReceipt->getAmount());
        $receipt->setDate($salesReceipt->getDate());

        $this->salesReceipts()->save($receipt);
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
        $card = new TimeCardModel();
        $card->setDate($timeCard->getDate());
        $card->setEmployeeId($timeCard->getEmployeeId());
        $card->setHours($timeCard->getHours());

        $this->timeCards()->save($card);
    }

    /**
     * @param string $date
     * @return TimeCard
     */
    public function getTimeCardBy($date)
    {
        return $this->timeCards()->where('date', $date)->get()->first();
    }

    /**
     * @param string $payDate
     * @return bool
     */
    public function isPayDay($payDate)
    {
        return $this->getPaymentSchedule()->isPayDay($payDate);
    }

    /**
     * @param Paycheck $paycheck
     */
    public function payday(Paycheck $paycheck)
    {
        $netPay = $this->getPaymentClassification()->calculatePay($paycheck);
        $paycheck->setNetPay($netPay);
        $paycheck->setEmployeeId($this->id);
        $paycheck->setType($this->getPaymentMethod()->getType());
        $this->addPaycheck($paycheck);
    }

    /**
     * @param Paycheck $paycheck
     */
    public function addPaycheck(Paycheck $paycheck)
    {
        $check = PaycheckFactory::create();
        $check->setDate($paycheck->getDate());
        $check->setEmployeeId($paycheck->getEmployeeId());
        $check->setNetPay($paycheck->getNetPay());
        $check->setType($paycheck->getType());

        $this->timeCards()->save($check);
    }

    public function getPaycheckBy($date)
    {
        // TODO: Implement getPaycheckBy() method.
    }

    public function getPaychecks()
    {
        return $this->paychecks()->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function paychecks()
    {
        return $this->hasMany(PaycheckModel::class);
    }

    /**
     * @return Collection|static[]
     */
    public function getAll()
    {
        return Employee::all();
    }
}
