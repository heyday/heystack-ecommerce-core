<?php

namespace Heystack\Ecommerce;

use SebastianBergmann\Money\Money;
use SebastianBergmann\Money\Currency;

/**
 * Converts a string price from the database (e.g. "19.90" in dollars) into a Money object
 *
 * @param float|string $value
 * @param \SebastianBergmann\Money\Currency $currency
 * @return \SebastianBergmann\Money\Money
 */
function convertStringToMoney($value, Currency $currency) {
    return new Money(convertStringToInt($value, $currency), $currency);
}

/**
 * Converts a string price from the database (e.g. "19.90" in dollars) into an int
 *
 * The function is designed to take into account the errors that can arise from floating point numbers
 *
 * If the number of decimals of the $value variable is more than the number of frational digits. e.g. 1000.60 Yen
 * Then the number will be rounded to the number of fractional digits. e.g the value of 1000.60 Yen is just 1000.00
 * 
 * @param float|string $value
 * @param \SebastianBergmann\Money\Currency $currency
 * @return int
 */
function convertStringToInt($value, Currency $currency) {
    $currencyMultiplier = $currency->getSubUnit();
    $valueInCentsAsFloat = $currencyMultiplier * round($value, $currency->getDefaultFractionDigits(), PHP_ROUND_HALF_UP);
    $valueInCentsAsFloatRounded = round($valueInCentsAsFloat, 0, PHP_ROUND_HALF_UP);
    return (int) $valueInCentsAsFloatRounded;
}

/**
 * Converts a Money value object to a string for the database reporting etc
 * @param \SebastianBergmann\Money\Money $value
 * @return string
 */
function convertMoneyToString(Money $value) {
    return convertIntToString($value->getAmount(), $value->getCurrency());
}

/**
 * Converts an int to a string for the database reporting etc
 * @param int $value
 * @param \SebastianBergmann\Money\Currency $currency
 * @return string
 */
function convertIntToString($value, Currency $currency) {
    $subunit = $currency->getSubUnit();

    return (string) round($value / $subunit, log10($subunit), PHP_ROUND_HALF_UP);
}
