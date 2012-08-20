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

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;

use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;

use Heystack\Subsystem\Ecommerce\Currency\Events;

use Heystack\Subsystem\Ecommerce\Currency\Event\CurrencyEvent;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Heystack\Subsystem\Core\Exception\ConfigurationException;

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
class CurrencyService implements CurrencyServiceInterface, StateableInterface
{
    /**
     * The key used on the data array to store the active currency
     */
    const ACTIVE_CURRENCY_KEY = 'activecurrency';

    /**
     * The key used on the data array to store all the currencies
     */
    const ALL_CURRENCIES_KEY = 'allcurrencies';

    /**
     * The key used on the data array to store the default currency
     */
    const DEFAULT_CURRENCY_KEY = 'defaultcurrency';

    /**
     * State Key constant
     */
    const IDENTIFIER = 'currency_service';

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
     * Stores the Global State Service
     * @var State
     */
    protected $globalState;

    /**
     * Stores session based information used by the CurrencyService
     * @var array
     */
    protected $data = array();

    /**
     * Stores global information used by the CurrencyService
     * @var array
     */
    protected $data_global = array();

    /**
     * The class name of the Currency Data Object
     * @var string
     */
    protected $currencyClass;

    /**
     * CurrencySerivce Constructor
     * @param string                                                      $currencyClass
     * @param \Heystack\Subsystem\Ecommerce\Currency\State                $state
     * @param \Heystack\Subsystem\Ecommerce\Currency\State                $globalState
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct($currencyClass, State $sessionState, State $globalState, EventDispatcherInterface $eventDispatcher)
    {
        $this->currencyClass = $currencyClass;
        $this->sessionState = $sessionState;
        $this->globalState = $globalState;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Uses the State service to restore the data array. It also sets all the
     * currencies/default currency on the data array if this is the first time
     * this method has been called.
     */
    public function restoreState()
    {

        $this->data = $this->sessionState->getByKey(self::IDENTIFIER);
        $this->ensureDataExists();

    }
    public function restoreGlobalState()
    {

        $this->data_global = $this->globalState->getByKey(self::IDENTIFIER);
        $this->ensureGlobalDataExists();

    }

    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {

        $this->sessionState->setByKey(self::IDENTIFIER, $this->data);

    }

    public function saveGlobalState()
    {

        $this->globalState->setByKey(self::IDENTIFIER, $this->data_global);

    }

    /**
     * If after restoring state no currencies are loaded onto the data array get
     * them from the database and load them to the data array, and save the state.
     * @throws \Exception
     */
    protected function ensureGlobalDataExists()
    {

        if (!$this->data_global || !isset($this->data_global[self::ALL_CURRENCIES_KEY]) || !isset($this->data_global[self::DEFAULT_CURRENCY_KEY])) {

            $filename = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache') . DIRECTORY_SEPARATOR . 'currencies.cache';

            $currencies = file_exists($filename) ? unserialize(file_get_contents($filename)) : false;

            if ($currencies instanceof \DataObjectSet) {

                $this->updateCurrencies($currencies, false);

            } else {

                $this->updateCurrencies(new \DataObjectSet);

                throw new ConfigurationException('Please add some currencies and save them');

            }

        }

    }

    protected function ensureDataExists()
    {

        if (!$this->data || !array_key_exists(self::ACTIVE_CURRENCY_KEY, $this->data)) {

            $defaultCurrency = $this->getDefaultCurrency();

            if ($defaultCurrency) {

                $this->data[self::ACTIVE_CURRENCY_KEY] = $this->getCurrency($defaultCurrency);

                $this->saveState();

            }

        }

    }

    /**
     * Sets the currently active Currency
     * @param string $identifier
     */
    public function setActiveCurrency($identifier)
    {
        if ($currency = $this->getCurrency($identifier)) {

            $this->data[self::ACTIVE_CURRENCY_KEY] = $currency;

            $this->eventDispatcher->dispatch(Events::CHANGED, new CurrencyEvent($currency));

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
        return isset($this->data[self::ACTIVE_CURRENCY_KEY]) && isset($this->data_global[self::ALL_CURRENCIES_KEY][$this->data[self::ACTIVE_CURRENCY_KEY]-])? $this->data_global[self::ALL_CURRENCIES_KEY][$this->data[self::ACTIVE_CURRENCY_KEY]] : null;
    }

    /**
     * Retrieves all the available currencies
     * @return array
     */
    public function getCurrencies()
    {
        return isset($this->data_global[self::ALL_CURRENCIES_KEY]) ? $this->data_global[self::ALL_CURRENCIES_KEY] : array();
    }

    /**
     * Converts amount from one currency to another using the currency's identifier
     * @param float  $amount
     * @param string $from
     * @param string $to
     */
    public function convert($amount, $from, $to)
    {
        return $amount * ($this->data_global[self::ALL_CURRENCIES_KEY][$to]->getValue() / $this->data_global[self::ALL_CURRENCIES_KEY][$from]->getValue());
    }

    /**
     * Returns a currency object based on the identifier
     * @param  type                                                               $identifier
     * @return Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getCurrency($identifier)
    {
        return isset($this->data_global[self::ALL_CURRENCIES_KEY][$identifier]) ? $this->data_global[self::ALL_CURRENCIES_KEY][$identifier] : null;
    }

    /**
     * Returns the default currency object
     * @return Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getDefaultCurrency()
    {

        if (isset($this->data_global[self::DEFAULT_CURRENCY_KEY])) {

            return $this->data_global[self::DEFAULT_CURRENCY_KEY];

        } else {

            return false;

        }
    }

    public function setDefaultCurrency($identifier = null)
    {

        if (!is_null($identifier)) {

            $this->data_global[self::DEFAULT_CURRENCY_KEY] = $identifier;

        } else {

            foreach ($this->data_global[self::ALL_CURRENCIES_KEY] as $currency) {

                if ($currency->isDefaultCurrency()) {

                    $this->data_global[self::DEFAULT_CURRENCY_KEY] = $currency->getIdentifier();

                }

            }

        }

    }

    public function updateCurrencies($currencies, $write = true)
    {

        $this->data_global[self::ALL_CURRENCIES_KEY] = $this->dosToArray($currencies);

        $this->setDefaultCurrency();

        $this->saveGlobalState();

        if ($write) {

            file_put_contents(
                realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache') . DIRECTORY_SEPARATOR . 'currencies.cache',
                serialize($currencies)
            );

        }

    }

    protected function dosToArray(\DataObjectSet $currencies)
    {

        $arr = array();

        foreach ($currencies as $currency) {

            $arr[$currency->getIdentifier()] = $currency;

        }

        return $arr;

    }

}
