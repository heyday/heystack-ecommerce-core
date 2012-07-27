<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Subsystem\Ecommerce\Transaction\Interfaces;

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
     * @param \Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionModifierInterface $modifier
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
     * @param string $type
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
     * Returns the currently active currency code
     */
    public function getCurrencyCode();

    /**
     * Sets the currently active currency code
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode);
    
    /**
     * Retrieves the Transaction's Collator
     */
    public function getCollator();
    
}
