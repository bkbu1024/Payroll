<?php

namespace Payroll\Contract\Base;

interface Identifiable
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);
}