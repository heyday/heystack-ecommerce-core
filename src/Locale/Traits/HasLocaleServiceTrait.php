<?php

namespace Heystack\Ecommerce\Locale\Traits;

use Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface;

/**
 * Class HasLocaleServiceTrait
 * @package Heystack\Ecommerce\Locale\Traits
 */
trait HasLocaleServiceTrait
{
    /**
     * @var \Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface
     */
    protected $localeService;

    /**
     * @param \Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface $localService
     * @return void
     */
    public function setLocaleService(LocaleServiceInterface $localService)
    {
        $this->localeService = $localService;
    }

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface
     */
    public function getLocaleService()
    {
        return $this->localeService;
    }
}