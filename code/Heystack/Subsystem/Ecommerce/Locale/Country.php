<?php

namespace Heystack\Subsystem\Ecommerce\Locale;

use Heystack\Subsystem\Core\Identifier\Identifier;
use Heystack\Subsystem\Ecommerce\Locale\Interfaces\CountryInterface;

/**
 * Class Country
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Heystack\Subsystem\Ecommerce\Locale
 */
class Country implements CountryInterface
{
    /**
     * @var
     */
    protected $code;
    /**
     * @var
     */
    protected $name;
    /**
     * @var bool
     */
    protected $default;
    /**
     * @param      $code
     * @param      $name
     * @param bool $default
     */
    public function __construct(
        $code,
        $name,
        $default = false
    ) {
        $this->code = $code;
        $this->name = $name;
        $this->default = $default;
    }
    /**
     *
     */
    public function serialize()
    {
        // TODO: Implement serialize() method.
    }
    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        // TODO: Implement unserialize() method.
    }
    /**
     * Returns a unique identifier
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->code);
    }
    /**
     * Returns the name of the country object
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Returns the country code of the country object
     */
    public function getCountryCode()
    {
        return $this->code;
    }
    /**
     * Returns a boolean indicating whether this is the default country
     */
    public function isDefault()
    {
        return $this->default;
    }
}
