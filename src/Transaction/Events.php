<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Transaction namespace
 */
namespace Heystack\Ecommerce\Transaction;

/**
 * Events definition for Transaction
 *
 * Contains all the event constants dispatched for/by the Transaction object
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
final class Events
{
    /**
     * Used to tell Transaction to update itself
     */
    const UPDATE = 'transaction.update';

    /**
     * Used to indicate that Transaction has finished updating
     */
    const UPDATED = 'transaction.updated';

    /**
     * Used to tell Transaction to 'store' itself
     */
    const STORE = 'transaction.store';

    /**
     * Used to indicate that Transaction has finished storing itself using the ss orm
     */
    const STORED = 'transaction.stored';
}
