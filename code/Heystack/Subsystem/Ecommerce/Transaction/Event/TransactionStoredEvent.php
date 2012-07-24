<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Event namespace
 */
namespace Heystack\Subsystem\Ecommerce\Transaction\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Currency Event
 *
 * Events dispatched from the CurrencyService will have this object attached
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class TransactionStoredEvent extends Event
{
    /**
     * Holds the stored transaction's ID from the database
     * @var int
     */
    protected $transactionID;

    /**
     * Creates the TransactionStoredEvent object
     * @param int $transactionID
     */
    public function __construct($transactionID)
    {
        $this->transactionID = $transactionID;
    }

    /**
     * Returns the transaction ID relevant to this event
     * @return int
     */
    public function getTransactionID()
    {
        return $this->transactionID;
    }
}