<?php

namespace Heystack\Ecommerce\Locale\Traits;

use Heystack\Ecommerce\Locale\Interfaces\ZoneServiceInterface;

/**
 * Class HasZoneServiceTrait
 * @package Heystack\Ecommerce\Locale\Traits
 */
trait HasZoneServiceTrait
{
    /**
     * @var \Heystack\Ecommerce\Locale\Interfaces\ZoneServiceInterface
     */
    protected $zoneService;

    /**
     * @param \Heystack\Ecommerce\Locale\Interfaces\ZoneServiceInterface $localService
     * @return void
     */
    public function setZoneService(ZoneServiceInterface $localService)
    {
        $this->zoneService = $localService;
    }

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\ZoneServiceInterface
     */
    public function getZoneService()
    {
        return $this->zoneService;
    }
}