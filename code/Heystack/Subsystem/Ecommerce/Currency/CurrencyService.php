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
class CurrencyService implements CurrencyServiceInterface, StateableInterface, \Serializable
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
    const STATE_KEY = 'currency_service';

    /**
     * Stores the State Service
     * @var State
     */
    protected $state;

    /**
     * Stores the EventDispatcher
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * Stores all the information used by the CurrencyService
     * @var array
     */
    protected $data = array();

    /**
     * The class name of the Currency Data Object
     * @var string
     */
    protected $currencyClass;

    /**
     * CurrencySerivce Constructor
     * @param string                                                      $currencyClass
     * @param \Heystack\Subsystem\Ecommerce\Currency\State                $state
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct($currencyClass, State $state, EventDispatcherInterface $eventDispatcher)
    {
        $this->currencyClass = $currencyClass;
        $this->state = $state;
        $this->eventDispatcher = $eventDispatcher;
    }

     /**
     * Returns a serialized string from the data array
     * @return string
     */
    public function serialize()
    {

        return serialize($this->data);

    }

    /**
     * Unserializes the data into this object's data array
     * @param string $data
     */
    public function unserialize($data)
    {

        $this->data = unserialize($data);

    }

    /**
     * Uses the State service to restore the data array. It also sets all the
     * currencies/default currency on the data array if this is the first time
     * this method has been called.
     */
    public function restoreState()
    {

        $this->data = $this->state->getObj(self::STATE_KEY);        

    }

    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {

        $this->state->setObj(self::STATE_KEY, $this->data);

    }
    
    /**
     * If after restoring state no currencies are loaded onto the data array get 
     * them from the database and load them to the data array, and save the state.
     * @throws \Exception
     */
    public function ensureDataExists()
    {
        if (!$this->data || !isset($this->data[self::ALL_CURRENCIES_KEY])) {
            $currencies = \DataObject::get($this->currencyClass);

            if ($currencies instanceof \DataObjectSet && $currencies->exists()) {

                foreach ($currencies as $currency) {
                    $this->data[self::ALL_CURRENCIES_KEY][$currency->getIdentifier()] = $currency;

                    if ($currency->isDefaultCurrency()) {
                        $this->data[self::DEFAULT_CURRENCY_KEY] = $currency;
                    }
                }

                if (!isset($this->data[self::ACTIVE_CURRENCY_KEY])) {
                    $this->setActiveCurrency($this->getDefaultCurrency()->getIdentifier());
                }
                
                $this->saveState();

            } else {
                throw new \Exception('Please create some currencies');
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
        $this->ensureDataExists();
        return isset($this->data[self::ACTIVE_CURRENCY_KEY]) ? $this->data[self::ACTIVE_CURRENCY_KEY] : null;
    }

    /**
     * Retrieves all the available currencies
     * @return array
     */
    public function getCurrencies()
    {
        $this->ensureDataExists();
        return isset($this->data[self::ALL_CURRENCIES_KEY]) ? $this->data[self::ALL_CURRENCIES_KEY] : null;
    }

    /**
     * Converts amount from one currency to another using the currency's identifier
     * @param float  $amount
     * @param string $from
     * @param string $to
     */
    public function convert($amount, $from, $to)
    {
        $this->ensureDataExists();
        return $amount * ($this->data[self::ALL_CURRENCIES_KEY][$to]->getValue() / $this->data[self::ALL_CURRENCIES_KEY][$from]->getValue());
    }

    /**
     * Returns a currency object based on the identifier
     * @param  type                                                               $identifier
     * @return Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getCurrency($identifier)
    {
        $this->ensureDataExists();
        return isset($this->data[self::ALL_CURRENCIES_KEY][$identifier]) ? $this->data[self::ALL_CURRENCIES_KEY][$identifier] : null;
    }

    /**
     * Returns the default currency object
     * @return Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getDefaultCurrency()
    {
        $this->ensureDataExists();
        return isset($this->data[self::DEFAULT_CURRENCY_KEY]) ? $this->data[self::DEFAULT_CURRENCY_KEY] : null;
    }
}
