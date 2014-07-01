<?php

namespace Heystack\Ecommerce\Locale;

use Heystack\Ecommerce\Locale\Interfaces\CountryInterface;
use Heystack\Ecommerce\Locale\Interfaces\ZoneInterface;
use Heystack\Ecommerce\Locale\Interfaces\ZoneServiceInterface;
use Heystack\Ecommerce\Locale\Traits\HasLocaleServiceTrait;

/**
 * @package Heystack\Ecommerce\Locale
 */
class ZoneService implements ZoneServiceInterface
{
    use HasLocaleServiceTrait;

    /**
     * @var \Heystack\Ecommerce\Locale\Interfaces\ZoneInterface[]
     */
    protected $zones;

    /**
     * @param LocaleService $localeService
     */
    public function __construct(LocaleService $localeService)
    {
        $this->localeService = $localeService;
    }

    /**
     * @param ZoneInterface $zone
     * @return void
     */
    public function addZone(ZoneInterface $zone)
    {
        $this->zones[$zone->getIdentifier()->getFull()] = $zone;
    }

    /**
     * @param array $zones
     * @return void
     */
    public function setZones(array $zones)
    {
        foreach ($zones as $zone) {
            $this->addZone($zone);
        }
    }

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\ZoneInterface[]
     */
    public function getZones()
    {
        return $this->zones;
    }

    /**
     * @return ZoneInterface
     */
    public function getActiveZone()
    {
        return $this->getZoneForCountry($this->localeService->getActiveCountry());
    }

    /**
     * @param CountryInterface $country
     * @return ZoneInterface
     * @throws \InvalidArgumentException
     */
    public function getZoneForCountry(CountryInterface $country)
    {
        $identifier = $country->getIdentifier();

        foreach ($this->zones as $zone) {
            if ($zone->hasCountry($identifier)) {
                return $zone;
            }
        }

        throw new \InvalidArgumentException(
            sprintf(
                "No zone exists for country '%s'",
                $identifier->getFull()
            )
        );
    }
} 