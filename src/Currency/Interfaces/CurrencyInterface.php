<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Interfaces namespace
 */
namespace Heystack\Ecommerce\Currency\Interfaces;

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
     * Returns the identifier
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier();

    /**
     * Returns the Currency's code, e.g. NZD, USD
     * @return string
     */
    public function getCurrencyCode();

    /**
     * Returns whether the currency is the System's default
     * @return bool
     */
    public function isDefaultCurrency();

    /**
     * Returns the value of the currency vis-a-vis the default currency
     * @return float
     */
    public function getValue();

    /**
     * Returns the default number of fraction digits used with this
     * currency.
     *
     * @return integer
     */
    public function getDefaultFractionDigits();

    /**
     * Returns the name that is suitable for displaying this currency.
     *
     * @return string
     */
    public function getDisplayName();

    /**
     * Returns the ISO 4217 numeric code of this currency.
     *
     * @return integer
     */
    public function getNumericCode();

    /**
     * Returns the ISO 4217 numeric code of this currency.
     *
     * @return integer
     */
    public function getSubUnit();
}
