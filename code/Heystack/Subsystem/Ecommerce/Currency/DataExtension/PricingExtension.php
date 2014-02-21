<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Extension namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency\DataExtension;

use FieldList;
use Heystack\Subsystem\Ecommerce\Currency\Traits\HasCurrencyServiceTrait;
use NumericField;
use Injector;
use DataExtension;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;
use Heystack\Subsystem\Ecommerce\Services;

/**
 * Pricing Extension
 *
 * Adds additional pricing fields based on the configured countries in the Locale Service
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class PricingExtension extends DataExtension
{
    use HasCurrencyServiceTrait;

    public static function get_extra_config($class, $extension, $args)
    {
        $db = [];

        $currencyService = Injector::inst()->create(sprintf('heystack.%s', Services::CURRENCY_SERVICE));

        if ($currencyService instanceof CurrencyServiceInterface) {

            foreach ($currencyService->getCurrencies() as $currency) {

                $db[$currency->getCurrencyCode() . "Price"] = 'Decimal(10, 2)';

            }

        }

        return [
            'db' => $db
        ];
    }

    public function updateCMSFields(FieldList $fields)
    {

        if ($this->currencyService instanceof CurrencyServiceInterface) {

            foreach ($this->currencyService->getCurrencies() as $currency) {

                $currencyCode = $currency->getCurrencyCode();

                $fields->removeByName($currencyCode . "Price");

                $fields->addFieldToTab(
                    'Root.Prices',
                    new NumericField($currencyCode . "Price", $currencyCode . " Price")
                );

            }

        }

        return $fields;
    }
}
