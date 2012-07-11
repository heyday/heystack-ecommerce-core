<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency\Interfaces;

/**
 * Defines what a Currency data object needs to implement 
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
interface CurrencyInterface
{
    /**
     * Returns the Currency's Symbol
     */
    public function retrieveSymbol();
    /**
     * Returns whether the currency is the System's default
     */
    public function isDefaultCurrency();
    /**
     * Returns the value of the currency vis-a-vis the default currency
     */
    public function retrieveValue();
}