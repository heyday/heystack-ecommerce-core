<?php

namespace Heystack\Ecommerce\Locale;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Ecommerce\Locale\Interfaces\ZoneInterface;
use Heystack\Ecommerce\Locale\Traits\HasLocaleServiceTrait;

/**
 * @package Heystack\Ecommerce\Locale
 */
class Zone implements ZoneInterface
{
    use HasLocaleServiceTrait;

    /**
     * @var
     */
    protected $name;

    /**
     * @var \Heystack\Ecommerce\Locale\Interfaces\CountryInterface[]
     */
    protected $countries = [];

    /**
     * @param LocaleService $localeService
     * @param $name
     * @param array $countries
     */
    public function __construct(LocaleService $localeService, $name, array $countries)
    {
        $this->localeService = $localeService;
        $this->name = $name;
        $this->setCountries($countries);
    }

    /**
     * Returns a unique identifier
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->name);
    }

    /**
     * Returns the name of the Zone object
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param IdentifierInterface $identifier
     * @return void
     * @throws \InvalidArgumentException
     */
    public function addCountry(IdentifierInterface $identifier)
    {
        if (!$this->localeService->hasCountry($identifier)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Country '%s' added to zone doesn't exist in locale service",
                    $identifier->getFull()
                )
            );
        }
        
        $this->countries[$identifier->getFull()] = $this->localeService->getCountry($identifier);
    }

    /**
     * @param array $countries
     * @return void
     */
    public function setCountries(array $countries)
    {
        foreach ($countries as $country) {
            $this->addCountry($country instanceof IdentifierInterface ?: new Identifier($country));
        }
    }

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\CountryInterface[]
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param IdentifierInterface $identifier
     * @return bool
     */
    public function hasCountry(IdentifierInterface $identifier)
    {
        return isset($this->countries[$identifier->getFull()]);
    }
}