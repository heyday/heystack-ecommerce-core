<?php

namespace Heystack\Ecommerce\Transaction\Interfaces;

/**
 * @package Heystack\Ecommerce\Transaction\Interfaces
 */
interface HasLinkedTransactionModifiersInterface
{
    /**
     * @return \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[]
     */
    public function getLinkedModifiers();
}