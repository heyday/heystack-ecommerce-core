<?php

namespace Heystack\Ecommerce\Transaction\Interfaces;

/**
 * Defines what functions a TransactionModifier Class needs to implement
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Heystack\Ecommerce\Transaction\Interfaces
 */
interface TransactionModifierInterface
{
    /**
     * Returns a unique identifier for use in the Transaction
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier();

    /**
     * Returns the total value of the TransactionModifier for use in the Transaction
     * 
     * @return \SebastianBergmann\Money\Money
     */
    public function getTotal();

    /**
     * Indicates the type of amount the modifier will return
     * Must return a constant from TransactionModifierTypes
     * @return string
     */
    public function getType();
}
