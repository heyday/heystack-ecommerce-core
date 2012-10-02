<?php

namespace Heystack\Subsystem\Ecommerce\Locale;

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;

use Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Monolog\Logger;

class LocaleService implements LocaleServiceInterface, StateableInterface
{
    /**
     * Holds the key for storing all countries in the data array
     */
    const ALL_COUNTRIES_KEY = 'allcountries';
    const ACTIVE_COUNTRY_KEY = 'activecountry';
    const DEFAULT_COUNTRY_KEY = 'defaultcountry';

    const IDENTIFIER = 'localeservice';

    protected $countryClass;
    protected $sessionState;
    protected $globalState;
    protected $eventService;
    protected $monologService;

    protected $data = array();
    protected $data_global = array();

    public function __construct($countryClass, State $sessionState, State $globalState, EventDispatcherInterface $eventService, Logger $monologService = null)
    {
        $this->countryClass = $countryClass;
        $this->sessionState = $sessionState;
        $this->globalState = $globalState;
        $this->eventService = $eventService;
        $this->monologService = $monologService;
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

    /**
     * Saves the data array on the State service
     */
    public function saveGlobalState()
    {

        $this->globalState->setByKey(self::IDENTIFIER, $this->data_global);

    }

    public function getCountryClass()
    {
        return $this->countryClass;
    }

    public function ensureGlobalDataExists()
    {

        if (!$this->data_global || !isset($this->data_global[self::ALL_COUNTRIES_KEY]) || !isset($this->data_global[self::DEFAULT_COUNTRY_KEY])) {

            $filename = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache') . DIRECTORY_SEPARATOR . 'countries.cache';

            $countries = file_exists($filename) ? unserialize(file_get_contents($filename)) : false;

            if ($countries instanceof \DataObjectSet) {

                $this->updateCountries($countries, false);

            } else {

                $this->updateCountries(new \DataObjectSet);

                $this->monologService->err('Configuration error: Please add some countries and save them');

            }

        }

    }

    /**
     * If after restoring state no countries are loaded onto the data array get
     * them from the database and load them to the data array, and save the state.
     * @throws \Exception
     */
    protected function ensureDataExists()
    {

        if (!$this->data || !array_key_exists(self::ACTIVE_COUNTRY_KEY, $this->data)) {

            $defaultCountry = $this->getDefaultCountry();

            if ($defaultCountry) {

                $this->data[self::ACTIVE_COUNTRY_KEY] = $defaultCountry;

                $this->saveState();

            }

        }

    }

    public function setActiveCountry($identifier)
    {
        if ($country = $this->getCountry($identifier)) {

            $this->data[self::ACTIVE_COUNTRY_KEY] = $identifier;

            $this->saveState();

            $this->eventService->dispatch(Events::CHANGED);
        }
    }

    public function getActiveCountry()
    {
        return isset($this->data[self::ACTIVE_COUNTRY_KEY]) && isset($this->data_global[self::ALL_COUNTRIES_KEY][$this->data[self::ACTIVE_COUNTRY_KEY]])? $this->data_global[self::ALL_COUNTRIES_KEY][$this->data[self::ACTIVE_COUNTRY_KEY]] : null;
    }

    /**
     * Uses the identifier to retrive the country object from the cache
     * @param  type                                                                  $identifier
     * @return \Heystack\Subsystem\Shipping\CountryBased\Interfaces\CountryInterface
     */
    public function getCountry($identifier)
    {
        return isset($this->data_global[self::ALL_COUNTRIES_KEY][$identifier]) ? $this->data_global[self::ALL_COUNTRIES_KEY][$identifier] : null;
    }

    /**
     * Returns an array of all countries from the cache
     * @return array
     */
    public function getCountries()
    {
        return isset($this->data_global[self::ALL_COUNTRIES_KEY]) ? $this->data_global[self::ALL_COUNTRIES_KEY] : null;
    }

    public function setDefaultCurrency($identifier = null)
    {

        if (!is_null($identifier)) {

            $this->data_global[self::DEFAULT_COUNTRY_KEY] = $identifier;

        } else {

            foreach ($this->data_global[self::ALL_COUNTRIES_KEY] as $country) {

                if ($country->isDefault()) {

                    $this->data_global[self::DEFAULT_COUNTRY_KEY] = $country->getIdentifier();

                }

            }

        }

    }

    public function getDefaultCountry()
    {
        if (isset($this->data_global[self::DEFAULT_COUNTRY_KEY])) {

            return $this->data_global[self::DEFAULT_COUNTRY_KEY];

        } else {

            return false;

        }
    }

    public function updateCountries($countries, $write = true)
    {

        $this->data_global[self::ALL_COUNTRIES_KEY] = $this->dosToArray($countries);

        $this->setDefaultCurrency();

        $this->saveGlobalState();

        if ($write) {

            file_put_contents(
                realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache') . DIRECTORY_SEPARATOR . 'countries.cache',
                serialize($countries)
            );

        }

    }

    protected function dosToArray(\DataObjectSet $countries)
    {

        $arr = array();

        foreach ($countries as $country) {

            $arr[$country->getIdentifier()] = $country;

        }

        return $arr;

    }
}
