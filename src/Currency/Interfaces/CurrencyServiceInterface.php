<?php

namespace Heystack\Ecommerce\Currency\Interfaces;

use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Core\State\StateableInterface;
use SebastianBergmann\Money\Money;

/**
 * Defines what a Currency Service needs to implement
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
interface CurrencyServiceInterface extends StateableInterface
{
    /**
     * Sets the currently active currency
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return bool true on success false on failure
     */
    public function setActiveCurrency(IdentifierInterface $identifier);

    /**
     * Retrieves the currently active currency
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getActiveCurrency();

    /**
     * Retrieves all the currencies
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface[]
     */
    public function getCurrencies();

    /**
     * Converts amount from one currency to another using the currency's identifier
     * @param  \SebastianBergmann\Money\Money $amount
     * @param  \Heystack\Core\Identifier\IdentifierInterface $to
     * @return \SebastianBergmann\Money\Money
     * @throws \InvalidArgumentException
     */
    public function convert(Money $amount, IdentifierInterface $to);

    /**
     * Retrieves a currency object based on the identifier
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getCurrency(IdentifierInterface $identifier);

    /**
     * Retrieves the default currency object
     * @return void
     */
    public function getDefaultCurrency();

    /**
     * Sets the default currency
     * @param IdentifierInterface $identifier
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setDefaultCurrency(IdentifierInterface $identifier);

    /**
     * Retrieves the currently active currency code
     * @return string
     */
    public function getActiveCurrencyCode();

    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getZeroMoney();
}
