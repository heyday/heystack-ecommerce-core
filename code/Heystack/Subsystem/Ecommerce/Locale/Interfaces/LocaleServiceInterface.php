<?php

namespace Heystack\Subsystem\Ecommerce\Locale\Interfaces;

use Heystack\Subsystem\Core\Identifier\IdentifierInterface;

interface LocaleServiceInterface
{
    public function setActiveCountry(IdentifierInterface $identifier);
    public function getActiveCountry();
    public function getCountry(IdentifierInterface $identifier);
    public function getCountries();
    public function getDefaultCountry();
}
