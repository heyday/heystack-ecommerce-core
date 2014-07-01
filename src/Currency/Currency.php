<?php

namespace Heystack\Ecommerce\Currency;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\ViewableData\ViewableDataInterface;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface;
use SebastianBergmann\Money\Currency as BaseCurrency;

/**
 * Class Currency
 * @author Cam Spiers <cameron@heyday.co.nz>
 */
class Currency extends BaseCurrency
    implements
        CurrencyInterface,
        ViewableDataInterface
{
    /**
     * The currency value
     * @var
     */
    protected $value;
    /**
     * Whether the currency is the default
     * @var bool
     */
    protected $default;

    /**
     * @param string $currencyCode
     * @param        $value
     * @param bool $default
     */
    public function __construct(
        $currencyCode,
        $value,
        $default = false
    )
    {
        $this->value = $value;
        $this->default = $default;
        parent::__construct($currencyCode);
    }

    /**
     * Returns the identifier
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->getCurrencyCode());
    }

    /**
     * Returns whether the currency is the System's default
     */
    public function isDefaultCurrency()
    {
        return $this->default;
    }

    /**
     * Returns the value of the currency vis-a-vis the default currency
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Defines what methods the implementing class implements dynamically through __get and __set
     */
    public function getDynamicMethods()
    {
        return [];
    }

    /**
     * Returns an array of SilverStripe DBField castings keyed by field name
     */
    public function getCastings()
    {
        return [];
    }
}
