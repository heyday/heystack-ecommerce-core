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
     * @return mixed
     */
    public function getZones();

    /**
     * @return mixed
     */
    public function getActiveZone();

    /**
     * @param \Heystack\Ecommerce\Locale\Interfaces\CountryInterface $country
     * @return mixed
     */
    public function getZoneForCountry(CountryInterface $country);
}