<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Transaction namespace
 */
namespace Heystack\Subsystem\Ecommerce\Transaction;

/**
 * Defines the types of Transaction Modifiers
 *
 * The type chosen determines how the Transaction object will add the TransactionModifer's total to its own total
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
final class TransactionModifierTypes
{
    /**
     * Indicates a positive sign on the Transaction Modifier's total
     */
    const CHARGEABLE = 'chargable';

    /**
     * Indicates a negative sign on the Transaction Modifier's total
     */
    const DEDUCTIBLE = 'deductible';

    /**
     * Indicates that the Transaction Modifier's total has no bearing on the Transaction object's total
     */
    const NEUTRAL = 'neutral';
}
