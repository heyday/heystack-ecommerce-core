<?php

namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyInterface;

/**
 * Class Currency
 * @author Cam Spiers <cameron@heyday.co.nz>
 */
class Currency implements CurrencyInterface
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
        $this->symbol;
    }
    /**
     * Returns the identifier
     */
    public function getIdentifier()
    {
        return $this->code;
    }
    /**
     * Returns the Currency's code, e.g. NZD, USD
     */
    public function getCurrencyCode()
    {
        $this->code;
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
}