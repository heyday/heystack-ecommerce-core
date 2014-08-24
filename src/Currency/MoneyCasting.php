<?php

namespace Heystack\Ecommerce\Currency;

use Heystack\Ecommerce\Currency\Traits\HasCurrencyServiceTrait;
use Heystack\Ecommerce\Locale\Traits\HasLocaleServiceTrait;
use SebastianBergmann\Money\IntlFormatter;
use SebastianBergmann\Money\Money;

/**
 * Class Money
 * @package Heystack\Ecommerce\Currency
 */
class MoneyCasting extends \ViewableData
{
    use HasLocaleServiceTrait;

    private static $casting = [
        'Nice'     => 'Text',
        'Currency' => 'Text',
        'Amount'   => 'Decimal'
    ];

    /**
     * @var \SebastianBergmann\Money\Money
     */
    protected $value;

    /**
     * @param \SebastianBergmann\Money\Money $value
     * @param mixed|null|void $record
     * @return \Heystack\Ecommerce\Currency\MoneyCasting
     */
    public function setValue(Money $value, $record = null)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Remove country codes that sometimes appear in front of currency symbols
     * @return string
     */
    public function Nice()
    {
        return preg_replace('/[A-Za-z]/', '', $this->getFormatter()->format($this->value));
    }

    /**
     * @return string
     */
    public function Currency()
    {
        return $this->value->getCurrency()->getCurrencyCode();
    }

    /**
     * @return string
     */
    public function Amount()
    {
        return \Heystack\Ecommerce\convertMoneyToString(
            $this->value
        );
    }

    /**
     * @return string
     */
    public function forTemplate()
    {
        return $this->Nice();
    }

    /**
     * @return \SebastianBergmann\Money\IntlFormatter
     */
    protected function getFormatter()
    {
        return new IntlFormatter(
            sprintf(
                'en-%s',
                $this->localeService->getActiveCountry()->getCountryCode()
            )
        );
    }

    /**
     * The value "exists" if it is not 0
     * @return bool
     */
    public function exists()
    {
        return $this->value->getAmount() !== 0;
    }

    /**
     * @param \SebastianBergmann\Money\Money $value
     */
    public static function castMoneyToString(Money $value)
    {
        return \Injector::inst()->get(__CLASS__)->setValue($value)->Nice();
    }
}