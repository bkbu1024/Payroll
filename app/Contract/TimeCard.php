<?php

namespace Payroll\Contract;

interface TimeCard
{
    public function getId();
    public function getEmployeeId();
    public function getDate();
    public function getHours();
    public function setId($id);
    public function setEmployeeId($employeeId);

    /**
     * @param string $date
     */
    public function setDate($date);
    public function setHours($hours);
}