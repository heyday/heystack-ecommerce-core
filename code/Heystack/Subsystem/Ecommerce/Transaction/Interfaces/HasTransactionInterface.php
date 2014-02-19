<?php

namespace Heystack\Subsystem\Ecommerce\Transaction\Interfaces;

/**
 * Interface HasnTransactionInterface
 * @package Heystack\Subsystem\Ecommerce\Transaction\Interfaces
 */
interface HasTransactionInterface
{
    /**
     * @param \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     */
    public function setTransaction(TransactionInterface $transaction);
    
    /**
     * @return \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface
     */
    public function getTransaction();
} 