<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Event namespace
 */
namespace Heystack\Ecommerce\Currency\Event;

use Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Currency Event
 *
 * Events dispatched from the CurrencyService will have this object attached
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class CurrencyEvent extends Event
{
    /**
     * Holds the currency object relevant to this event
     * @var \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    protected $currency;

    /**
     * Creates the CurrencyEvent object
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface $currency
     */
    public function __construct(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }

    /**
     * Returns the currency object relevant to this event
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
