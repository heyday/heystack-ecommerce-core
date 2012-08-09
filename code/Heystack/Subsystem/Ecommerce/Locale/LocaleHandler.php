<?php

namespace Heystack\Subsystem\Ecommerce\Locale;

use Heystack\Subsystem\Core\State\State;
use Heystack\Subsystem\Core\State\StateableInterface;

use Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleHandlerInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Monolog\Logger;

class LocaleHandler implements LocaleHandlerInterface, StateableInterface, \Serializable
{
    /**
     * Holds the key for storing all countries in the data array
     */
    const ALL_COUNTRIES_KEY = 'allcountries';
    const ACTIVE_COUNTRY_KEY = 'activecountry';
    const DEFAULT_COUNTRY_KEY = 'defaultcountry';
    
    const IDENTIFIER = 'localehandler';
    
    protected $countryClass;
    protected $sessionState;
    protected $globalState;
    protected $eventService;    
    protected $monologService;
    
    protected $data = array();

    public function __construct($countryClass, State $sessionState, State $globalState, EventDispatcherInterface $eventService, Logger $monologService = null)
    {
        $this->countryClass = $countryClass;
        $this->sessionState = $sessionState;
        $this->globalState = $globalState;
        $this->eventService = $eventService;
        $this->monologService = $monologService;
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

        $this->data = $this->sessionState->getByKey(self::IDENTIFIER);

    }

    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {

        $this->sessionState->setByKey(self::IDENTIFIER, $this->data);

    }
    
    public function getCountryClass()
    {
        return $this->countryClass;
    }
    
    /**
     * If after restoring state no countries are loaded onto the data array get
     * them from the database and load them to the data array, and save the state.
     * @throws \Exception
     */
    protected function ensureDataExists()
    {
        if (!$this->data || !isset($this->data[self::ALL_COUNTRIES_KEY])) {
            $countries = $this->globalState->getByKey(self::ALL_COUNTRIES_KEY);
            
            if (!isset($countries) || !$countries){
                
                // if the global state falls over get it off disk
                
                $filename = realpath(BASE_PATH . DIRECTORY_SEPARATOR . 'heystack/cache') . DIRECTORY_SEPARATOR . 'countries.cache';
                
                if (file_exists($filename)) {
                
                    $countries = unserialize(file_get_contents($filename));
                    
                } else if (\Director::is_cli() || strpos ($_SERVER['REQUEST_URI'], 'admin') !== false) {
                
                    $countries = new \DataObjectSet;
                
                }
                
                $this->globalState->setByKey(self::ALL_COUNTRIES_KEY, $countries);

            }

            if ($countries instanceof \DataObjectSet && $countries->exists()) {

                foreach ($countries as $country) {
                    
                    if($country instanceof $this->countryClass){
                    
                        $this->data[self::ALL_COUNTRIES_KEY][$country->getIdentifier()] = $country;

                        if ($country->isDefault()){
                            $this->data[self::DEFAULT_COUNTRY_KEY] = $country;
                        }
                    }else{
                        if (isset($this->monologService)) {
                            $this->monologService->err('Configuration error: Trying to add a country object that is not an instance of ' . $this->countryClass);
                        }

                        throw new \Exception('Configuration error: Trying to add a country object that is not an instance of ' . $this->countryClass);
                    }
                }
                
                if (!isset($this->data[self::ACTIVE_COUNTRY_KEY]) && $countries->exists()) {
                    $this->setActiveCountry($this->getDefaultCountry()->getIdentifier());
                }

                $this->saveState();

            } else {

                if (isset($this->monologService)) {
                    $this->monologService->err('Please create some countries or save a record to instantiate the cache for the first time.');
                }

                throw new \Exception('Please create some countries or save a record to instantiate the cache for the first time.');
            }
        }
    }
    
    public function setActiveCountry($identifier)
    {
        if ($country = $this->getCountry($identifier)) {

            $this->data[self::ACTIVE_COUNTRY_KEY] = $country;
            
            $this->saveState();
            
            $this->eventService->dispatch(Events::CHANGED);
        }
    }
    
    public function getActiveCountry()
    {        
        return isset($this->data[self::ACTIVE_COUNTRY_KEY]) ? $this->data[self::ACTIVE_COUNTRY_KEY] : null;
    }

    /**
     * Uses the identifier to retrive the country object from the cache
     * @param  type                                                                  $identifier
     * @return \Heystack\Subsystem\Shipping\CountryBased\Interfaces\CountryInterface
     */
    public function getCountry($identifier)
    {
        $this->ensureDataExists();

        return isset($this->data[self::ALL_COUNTRIES_KEY][$identifier]) ? $this->data[self::ALL_COUNTRIES_KEY][$identifier] : null;
    }

    /**
     * Returns an array of all countries from the cache
     * @return array
     */
    public function getCountries()
    {
        $this->ensureDataExists();

        return isset($this->data[self::ALL_COUNTRIES_KEY]) ? $this->data[self::ALL_COUNTRIES_KEY] : null;
    }
    
    public function getDefaultCountry()
    {
        $this->ensureDataExists();
        
        if (isset($this->data[self::DEFAULT_COUNTRY_KEY])) {
            
            return $this->data[self::DEFAULT_COUNTRY_KEY];
            
        } else {
            
            if (isset($this->monologService)) {
                
                $this->monologService->err('Please select a default country locale');
                
            }
            
            throw new \Exception('Please select a default country locale');
            
        }
    }
}
