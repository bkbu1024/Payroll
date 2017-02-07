<?php

namespace Payroll\Contract\Base;

interface Nameable
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);
}