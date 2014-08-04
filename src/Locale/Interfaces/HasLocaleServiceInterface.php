<?php

namespace Heystack\Ecommerce\Locale\Interfaces;

/**
 * Interface HasLocaleServiceInterface
 * @package Heystack\Ecommerce\Locale\Interfaces
 */
interface HasLocaleServiceInterface
{
    /**
     * @param \Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface $localService
     * @return void
     */
    public function setLocaleService(LocaleServiceInterface $localService);

    /**
     * @return \Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface
     */
    public function getLocaleService();
}