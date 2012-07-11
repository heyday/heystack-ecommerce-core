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
 * CurrencyEvent emitted by CurrencyService.
 *
 * All events emitted that are related to Currency will use this.
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class CurrencyEvent extends Event
{
    /**
     * Currency storage
     * @var \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    protected $currency;

    /**
     * CurrencyEvent Contstructor
     * @param \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface $currency
     */
    public function __construct(CurrencyInterface $currency)
    {
        $this->currency = $currency;
    }

    /**
     * Returns the currency related to this event
     * @return \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}
