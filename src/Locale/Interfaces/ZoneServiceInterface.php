<?php

namespace Heystack\Ecommerce\Locale\Interfaces;

/**
 * @package Heystack\Ecommerce\Locale\Interfaces
 */
interface ZoneServiceInterface
{
    /**
     * @param \Heystack\Ecommerce\Locale\Interfaces\ZoneInterface $zone
     * @return mixed
     */
    public function addZone(ZoneInterface $zone);

    /**
     * @param array $zones
     * @return mixed
     */
    public function setZones(array $zones);

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\ZoneInterface[]
     */
    public function getZones();

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\ZoneInterface
     */
    public function getActiveZone();

    /**
     * @param \Heystack\Ecommerce\Locale\Interfaces\CountryInterface $country
     * @return \Heystack\Ecommerce\Locale\Interfaces\ZoneInterface
     */
    public function getZoneForCountry(CountryInterface $country);
}