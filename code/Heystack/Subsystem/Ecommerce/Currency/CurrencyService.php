<?php
/**
 * This file is part of the Ecommerce-Products package
 *
 * @package Ecommerce-Core
 */

/**
 * Currency namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Core\Identifier\IdentifierInterface;
use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;
use Heystack\Subsystem\Ecommerce\Currency\Event\CurrencyEvent;
use Heystack\Subsystem\Ecommerce\Currency\Events;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * CurrencyService default implementation
 *
 * This handles all the operations needed keep track of and calculate currencies and their values
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class CurrencyService implements CurrencyServiceInterface, StateableInterface
{
    /**
     * The key used on the data array to store the active currency
     */
    const ACTIVE_CURRENCY_KEY = 'currencyservice.activecurrency';
    /**
     * Stores the State Service
     * @var State
     */
    protected $sessionState;
    /**
     * Stores the EventDispatcher
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;
    /**
     * An array of currencies
     * @var array
     */
    protected $currencies;
    /**
     * @var Interfaces\CurrencyInterface
     */
    protected $activeCurrency;
    /**
     * @var Interfaces\CurrencyInterface
     */
    protected $defaultCurrency;
    /**
     * CurrencySerivce Constructor
     * @param array                                                       $currencies
     * @param                                                             $defaultCurrency
     * @param \Heystack\Subsystem\Core\State\State                        $sessionState
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        array $currencies,
        CurrencyInterface $defaultCurrency,
        State $sessionState,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->setCurrencies($currencies);
        $this->defaultCurrency = $this->activeCurrency = $defaultCurrency;
        $this->sessionState = $sessionState;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * @param array $currencies
     */
    protected function setCurrencies(array $currencies)
    {
        foreach ($currencies as $currency) {
            $this->addCurrency($currency);
        }
    }
    /**
     * @param CurrencyInterface $currency
     */
    protected function addCurrency(CurrencyInterface $currency)
    {
        $this->currencies[$currency->getIdentifier()->getFull()] = $currency;
    }
    /**
     * Uses the State service to restore the data array. It also sets all the
     * currencies/default currency on the data array if this is the first time
     * this method has been called.
     */
    public function restoreState()
    {
        if ($identifier = $this->sessionState->getByKey(self::ACTIVE_CURRENCY_KEY)) {
            $this->setActiveCurrency($identifier, false);
        }
    }
    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {
        $this->sessionState->setByKey(
            self::ACTIVE_CURRENCY_KEY,
            $this->activeCurrency->getIdentifier()
        );
    }
    /**
     * @param IdentifierInterface $identifier
     * @param bool $saveState Determines whether the state is saved and the update event is dispatched
     * @return bool
     */
    public function setActiveCurrency(IdentifierInterface $identifier, $saveState = true)
    {
        if ($currency = $this->getCurrency($identifier)) {
            $this->activeCurrency = $currency;

            if ($saveState) {
                $this->saveState();
                $this->eventDispatcher->dispatch(
                    Events::CHANGED,
                    new CurrencyEvent(
                        $currency
                    )
                );
            }

            return true;
        }

        return false;
    }
    /**
     * Retrieves the currently active currency
     * @return \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getActiveCurrency()
    {
        return $this->activeCurrency;
    }
    /**
     * Retrieves the currently active currency code
     * @return string
     */
    public function getActiveCurrencyCode()
    {
        return $this->getActiveCurrency()->getCurrencyCode();
    }
    /**
     * Retrieves all the available currencies
     * @return array
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }
    /**
     * Converts amount from one currency to another using the currency's identifier
     * @param  float  $amount
     * @param  string $from
     * @param  string $to
     * @return float
     */
    public function convert($amount, $from, $to)
    {
        return $amount * ($this->currencies[$to]->getValue() / $this->currencies[$from]->getValue());
    }
    /**
     * @param IdentifierInterface $identifier
     * @return \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface || null
     */
    public function getCurrency(IdentifierInterface $identifier)
    {
        return isset($this->currencies[$identifier->getFull()]) ? $this->currencies[$identifier->getFull()] : null;
    }
    /**
     * Returns the default currency object
     * @return \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }
    /**
     * @param null $identifier
     */
    public function setDefaultCurrency($identifier = null)
    {
        if (!is_null($identifier)) {
            $this->defaultCurrency = $this->currencies[$identifier];
        } else {
            foreach ($this->currencies as $currency) {
                if ($currency->isDefaultCurrency()) {
                    $this->defaultCurrency = $currency;
                }
            }
        }
    }
}
