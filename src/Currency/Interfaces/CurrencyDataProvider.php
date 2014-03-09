<?php

namespace Heystack\Ecommerce\Currency\Interfaces;

/**
 * Interface CurrencyDataProvider
 * @package Heystack\Ecommerce\Currency\Interfaces
 */
interface CurrencyDataProvider
{
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
}