<?php

namespace Heystack\Subsystem\Ecommerce\Locale\Interfaces;

/**
 * Interface HasLocaleServiceInterface
 * @package Heystack\Subsystem\Ecommerce\Locale\Interfaces
 */
interface HasLocaleServiceInterface
{
    /**
     * @param \Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface $localService
     */
    public function setLocaleService(LocaleServiceInterface $localService);

    /**
     * @return \Heystack\Subsystem\Ecommerce\Locale\Interfaces\LocaleServiceInterface
     */
    public function getLocaleService();
}