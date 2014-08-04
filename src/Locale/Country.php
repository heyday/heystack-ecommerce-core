<?php

namespace Heystack\Ecommerce\Locale;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\ViewableData\ViewableDataInterface;
use Heystack\Ecommerce\Locale\Interfaces\CountryInterface;

/**
 * Class Country
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Ecommerce\Locale
 */
class Country implements CountryInterface, ViewableDataInterface
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $default;

    /**
     * @param string $code
     * @param string $name
     * @param bool $default
     */
    public function __construct(
        $code,
        $name,
        $default = false
    )
    {
        $this->code = $code;
        $this->name = $name;
        $this->default = $default;
    }

    /**
     * Returns a unique identifier
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->code);
    }

    /**
     * Returns the name of the country object
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the country code of the country object
     * @return string
     */
    public function getCountryCode()
    {
        return $this->code;
    }

    /**
     * Returns a boolean indicating whether this is the default country
     * @return bool
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Defines what methods the implementing class implements dynamically through __get and __set
     * @return array
     */
    public function getDynamicMethods()
    {
        return [];
    }

    /**
     * Returns an array of SilverStripe DBField castings keyed by field name
     * @return array
     */
    public function getCastings()
    {
        return [];
    }
}
