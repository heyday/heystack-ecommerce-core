<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Event namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency\Event;

use Symfony\Component\EventDispatcher\Event;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;

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
     * @var \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    protected $currency;

    /**
     * Creates the CurrencyEvent object
     * @param \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface $currency
     */
    public function __construct(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }

    /**
     * Returns the currency object relevant to this event
     * @return \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
