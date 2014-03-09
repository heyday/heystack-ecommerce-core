<?php

namespace Heystack\Ecommerce\Transaction\Traits;

/**
 * Class TransactionModifierHasDataTrait
 * @package Heystack\Ecommerce\Transaction\Traits
 */
trait TransactionModifierHasDataTrait
{
    /**
     * Ensure class that uses this trait has the setData method
     * @param mixed $data
     * @return void
     */
    abstract public function setData($data);

    /**
     * Ensure class that uses this trait has the getData method
     * @return mixed
     */
    abstract public function getData();
}