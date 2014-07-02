<?php

namespace Heystack\Ecommerce\Locale\Interfaces;

/**
 * @package Heystack\Ecommerce\Locale\Interfaces
 */
interface LocaleDetectionInterface
{
    /**
     * @param \SS_HTTPRequest $request
     * @return mixed
     */
    public function getCountryForRequest(\SS_HTTPRequest $request);

    /**
     * @param \SS_HTTPRequest $request
     * @return mixed
     */
    public function getZoneForRequest(\SS_HTTPRequest $request);
}