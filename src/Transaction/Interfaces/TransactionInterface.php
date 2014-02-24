<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Ecommerce\Transaction\Interfaces;

/**
 * Defines what functions a Transaction Class needs to implement
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
interface TransactionInterface
{
    /**
     * Add a TransactionModifier to the Transaction
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface $modifier
     */
    public function addModifier(TransactionModifierInterface $modifier);

    /**
     * Returns a TransactionModifier based on the identifier
     * @param string $identifier
     */
    public function getModifier($identifier);

    /**
     * Returns all the TransactionModifiers held by the Transaction object
     */
    public function getModifiers();

    /**
     * Returns modifiers on the transaction by TranactionModifierType
     * @param  string $type
     * @return array
     */
    public function getModifiersByType($type);

    /**
     * Returns the aggregate total of the TransactionModifers held by the Transaction object
     */
    public function getTotal();

    /**
     * Update the aggregate total of the TransactionModifers held by the Transaction object
     */
    public function updateTotal();

    /**
     * Retrieves the Transaction's Collator
     */
    public function getCollator();

    /**
     * Retrieves the total without adding excluded modifiers
     * @param array $exclude an array of identifiers to be excluded
     */
    public function getTotalWithExclusions(array $exclude);

    /**
     * Sets the status of the transaction
     * @param string $status the status of the transaction
     */
    public function setStatus($status);

    /**
     * Retrieves the Transaction's status
     */
    public function getStatus();

}
