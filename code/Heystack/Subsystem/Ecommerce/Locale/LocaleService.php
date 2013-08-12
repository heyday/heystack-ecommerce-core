<?php

namespace Heystack\Subsystem\Ecommerce\Locale;

use Heystack\Subsystem\Core\Identifier\IdentifierInterface;
use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;
use Heystack\Subsystem\Ecommerce\Locale\Interfaces\CountryInterface;
use Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class LocaleService
 * @author  Cam Spiers <cameron@heyday.co.nz>
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
        array $countries,
        CountryInterface $defaultCountry,
        State $sessionState,
        EventDispatcherInterface $eventService
    ) {
        $this->setCountries($countries);
        $this->defaultCountry = $this->activeCountry = $defaultCountry;
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
        $this->countries[$country->getIdentifier()->getFull()] = $country;
    }

    /**
     * Uses the State service to restore the data array. It also sets all the
     * currencies/default currency on the data array if this is the first time
     * this method has been called.
     */
    public function restoreState()
    {
        if ($identifier = $this->sessionState->getByKey(self::ACTIVE_COUNTRY_KEY)) {
            $this->setActiveCountry($identifier, false);
        }
    }
    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {
        $this->sessionState->setByKey(
            self::ACTIVE_COUNTRY_KEY,
            $this->activeCountry->getIdentifier()
        );
    }
    /**
     * @param      $identifier
     * @param bool $saveState  Determines whether the state is saved and the update event is dispatched
     */
    public function setActiveCountry(IdentifierInterface $identifier, $saveState = true)
    {
        if ($country = $this->getCountry($identifier)) {
            $this->activeCountry = $country;

            if ($saveState) {
                $this->saveState();
                $this->eventService->dispatch(
                    Events::CHANGED
                );
            }
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
     * Uses the identifier to retrieve the country object from the cache
     * @param IdentifierInterface $identifier
     * @return \Heystack\Subsystem\Shipping\CountryBased\Interfaces\CountryInterface || null
     */
    public function getCountry(IdentifierInterface $identifier)
    {
        return isset($this->countries[$identifier->getFull()]) ? $this->countries[$identifier->getFull()] : null;
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
