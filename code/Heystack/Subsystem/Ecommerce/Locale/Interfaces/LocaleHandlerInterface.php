<?php

namespace Heystack\Subsystem\Ecommerce\Locale\Interfaces;

interface LocaleHandlerInterface
{
    public function setActiveCountry($identifier);
    public function getActiveCountry();
    public function getCountry($identifier);
    public function getCountries();
    public function getDefaultCountry();
    public function getCountryClass();
}
