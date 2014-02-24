<?php

namespace Heystack\Ecommerce\Currency;

use Heystack\Core\ViewableData\ViewableDataInterface;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface;
use Heystack\Core\Identifier\Identifier;

/**
 * Class Currency
 * @author Cam Spiers <cameron@heyday.co.nz>
 */
class Currency implements CurrencyInterface, ViewableDataInterface
{
    /**
     * The currency code
     * @var
     */
    protected $code;
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
     * The currencies symbol
     * @var
     */
    protected $symbol;
    /**
     * @param        $code
     * @param        $value
     * @param bool   $default
     * @param string $symbol
     */
    public function __construct(
        $code,
        $value,
        $default = false,
        $symbol = '$'
    ) {
        $this->code = $code;
        $this->value = $value;
        $this->default = $default;
        $this->symbol = $symbol;
    }
    /**
     * Returns the identifier
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier($this->code);
    }
    /**
     * Returns the Currency's code, e.g. NZD, USD
     */
    public function getCurrencyCode()
    {
        return $this->code;
    }
    /**
     * Returns the Currency's Symbol, e.g. $,
     */
    public function getSymbol()
    {
        return $this->symbol;
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
