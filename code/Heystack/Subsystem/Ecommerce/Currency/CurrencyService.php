<?php
/**
 * This file is part of the Ecommerce-Products package
 *
 * @package Ecommerce-Products
 */

/**
 * Currency namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;

use Heystack\Subsystem\Ecommerce\Currency\Events;
use Heystack\Subsystem\Ecommerce\Currency\Event\CurrencyEvent;

/**
 * CurrencyService default implementation
 *
 * This handles all the operations needed keep track of and calculate currencies and their values
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class CurrencyService implements CurrencyServiceInterface
{
    /**
     * The key used by the state service to store the active currency
     */
    const ACTIVE_CURRENCY_KEY = 'activecurrencykey';
    
    /**
     * Stores the State Service
     * @var State 
     */
    private $state;
    
    /**
     * Stores the EventDispatcher
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * CurrencySerivce Constructor
     * @param \Heystack\Subsystem\Ecommerce\Currency\State $state
     * @param \Heystack\Subsystem\Ecommerce\Currency\EventDispatcher $eventDispatcher
     */
    public function __construct(State $state, EventDispatcher $eventDispatcher)
    {
        $this->state = $state;
        $this->eventDispatcher = $eventDispatcher;
    }
    
    /**
     * Sets the currently active Currency
     * @param \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface $currency
     */
    public function setCurrency(CurrencyInterface $currency)
    {
        $this->state->setByKey(self::ACTIVE_CURRENCY_KEY, $currency);
                
        $this->eventDispatcher->dispatch(Events::CURRENCY_CHANGE, new CurrencyEvent($currency));
    }
}
