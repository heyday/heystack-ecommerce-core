<?php

namespace Heystack\Subsystem\Ecommerce\Locale;

use Heystack\Subsystem\Core\Identifier\Identifier;
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
    protected function setCountries(array $countries)
    {
        foreach ($countries as $country) {
            $this->addCountry($country);
        }
    }
    /**
     * @param CountryInterface $country
     */
    protected function addCountry(CountryInterface $country)
    {
        $this->countries[$country->getIdentifier()->getFull()] = $country;
    }
    /**
     * Uses the State service to retrieve the active country's identifier and sets the active country.
     *
     * If the retrieved identifier is not an instance of the Identifier Interface, then it checks if it is a string,
     * which it uses to create a new Identifier object to set the active country.
     *
     */
    public function restoreState()
    {
        if ($identifier = $this->sessionState->getByKey(self::ACTIVE_COUNTRY_KEY)) {

            if ($identifier instanceof IdentifierInterface) {
                $this->setActiveCountry($identifier, false);
            } else if (is_string($identifier)) {
                $this->setActiveCountry(new Identifier($identifier), false);
            }

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
        if ($identifier instanceof IdentifierInterface) {

            return isset($this->countries[$identifier->getFull()]) ? $this->countries[$identifier->getFull()] : null;

        }

        return null;
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
     * @return bool true on success and false on failure
     */
    public function setDefaultCountry(IdentifierInterface $identifier = null)
    {
        if (!is_null($identifier) && isset($this->countries[$identifier->getFull()])) {
            $this->defaultCountry = $this->countries[$identifier->getFull()];
            return true;
        } else {
            foreach ($this->countries as $country) {
                if ($country->isDefault()) {
                    $this->defaultCountry = $country;
                    return true;
                }
            }
        }

        return false;
    }
    /**
     * @return mixed
     */
    public function getDefaultCountry()
    {
        return $this->defaultCountry;
    }
}
