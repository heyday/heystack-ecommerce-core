<?php

namespace Heystack\Subsystem\Ecommerce\Locale\Traits;

use Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface;

/**
 * Class HasLocaleServiceTrait
 * @package Heystack\Subsystem\Ecommerce\Locale\Traits
 */
trait HasLocaleServiceTrait
{
    /**
     * @var \Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface
     */
    protected $localeService;

    /**
     * @param \Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface $localService
     */
    public function setLocaleService(LocaleServiceInterface $localService)
    {
        $this->localeService = $localService;
    }

    /**
     * @return \Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface
     */
    public function getLocaleService()
    {
        return $this->localeService;
    }
} 