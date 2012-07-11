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
 * Defines what a Currency Service needs to implement
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
interface CurrencyServiceInterface
{
    /**
     * Sets the currently active currency
     * @param \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface $currency
     */
    public function setActiveCurrency($identifier);

    /**
     * Retrieves the currently active currency
     */
    public function getActiveCurrency();

    /**
     * Retrieves all the currencies
     */
    public function getCurrencies();

    /**
     * Converts amount from one currency to another using the currency's identifier
     * @param float  $amount
     * @param string $from
     * @param string $to
     */
    public function convert($amount, $from, $to);
    
    /**
     * Retrieves a currency object based on the identifier
     * @param type $identifier
     */
    public function getCurrency($identifier);
    
    /**
     * Retrieves the default currency object
     */
    public function getDefaultCurrency();
}
