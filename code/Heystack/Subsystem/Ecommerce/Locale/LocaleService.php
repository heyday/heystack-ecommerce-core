<?php

namespace Heystack\Subsystem\Ecommerce\Locale;

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;
use Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface;
use Heystack\Subsystem\Ecommerce\Locale\Interfaces\CountryInterface;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class LocaleService
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Subsystem\Ecommerce\Locale
 */
class LocaleService implements LocaleServiceInterface, StateableInterface
{
    /**
     * The active country key
     */
    const ACTIVE_COUNTRY_KEY = 'localservice.activecountry';
    /**
     * @var
     */
    protected $countries;
    /**
     * @var
     */
    protected $defaultCountry;
    /**
     * @var
     */
    protected $activeCountry;
    /**
     * @var \Heystack\Subsystem\Core\State\State
     */
    protected $sessionState;
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventService;

    /**
     * @param array                    $countries
     * @param CountryInterface         $defaultCountry
     * @param State                    $sessionState
     * @param EventDispatcherInterface $eventService
     */
    public function __construct(
        array $countries = array(),
        CountryInterface $defaultCountry,
        State $sessionState,
        EventDispatcherInterface $eventService
    ) {
        $this->setCountries($countries);
        $this->defaultCounty = $this->activeCountry = $defaultCountry;
        $this->sessionState = $sessionState;
        $this->eventService = $eventService;
    }
    /**
     * @param array $countries
     */
    public function setCountries(array $countries)
    {
        foreach ($countries as $country) {
            $this->addCountry($country);
        }
    }
    /**
     * @param CountryInterface $country
     */
    public function addCountry(CountryInterface $country)
    {
        $this->countries[$country->getIdentifier()] = $country;
    }

    /**
     * Uses the State service to restore the data array. It also sets all the
     * currencies/default currency on the data array if this is the first time
     * this method has been called.
     */
    public function restoreState()
    {
        if ($identifier = $this->sessionState->getByKey(self::ACTIVE_COUNTRY_KEY)) {
            $this->setActiveCountry($identifier);
        }
    }
    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {
        $this->sessionState->setByKey(self::ACTIVE_CURRENCY_KEY, $this->activeCountry->getIdentifier());
    }
    /**
     * @param $identifier
     */
    public function setActiveCountry($identifier)
    {
        if ($country = $this->getCountry($identifier)) {
            $this->activeCountry = $country;
            $this->saveState();
            $this->eventService->dispatch(
                Events::CHANGED
            );
        }
    }
    /**
     * @return mixed
     */
    public function getActiveCountry()
    {
        return $this->activeCountry;
    }
    /**
     * Uses the identifier to retrive the country object from the cache
     * @param  type                                                                  $identifier
     * @return \Heystack\Subsystem\Shipping\CountryBased\Interfaces\CountryInterface
     */
    public function getCountry($identifier)
    {
        return isset($this->countries[$identifier]) ? $this->countries[$identifier] : null;
    }

    /**
     * Returns an array of all countries from the cache
     * @return array
     */
    public function getCountries()
    {
        return $this->countries;
    }
    /**
     * @param null $identifier
     */
    public function setDefaultCountry($identifier = null)
    {
        if (!is_null($identifier)) {
            $this->defaultCountry = $this->countries[$identifier];
        } else {
            foreach ($this->countries as $country) {
                if ($country->isDefault()) {
                    $this->defaultCountry = $country;
                }
            }
        }
    }
    /**
     * @return mixed
     */
    public function getDefaultCountry()
    {
        return $this->defaultCountry;
    }
}
