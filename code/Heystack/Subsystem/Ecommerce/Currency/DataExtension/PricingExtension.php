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

use Heystack\Subsystem\Ecommerce\Currency\CurrencyService;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;

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
class PricingExtension extends \DataExtension
{
    protected $currencyService;

    public function setCurrencyService(CurrencyServiceInterface $service)
    {
        $this->currencyService = $service;
    }

    public static function get_extra_config($class, $extension, $args)
    {

        $db = array();

        $currencyService = \Injector::inst()->create('heystack.currency_service');

        if ($currencyService instanceof CurrencyServiceInterface) {

            foreach ($currencyService->getCurrencies() as $currency) {

                $db[$currency->getCurrencyCode() . "Price"] = 'Decimal(10, 2)';

            }

        }

        return array(
            'db' => $db
        );

    }

    public function updateCMSFields(FieldList $fields)
    {

        if ($this->currencyService instanceof CurrencyServiceInterface) {

            foreach ($this->currencyService->getCurrencies() as $currency) {

                $currencyCode = $currency->getCurrencyCode();

                $fields->removeByName($currencyCode . "Price");

                $fields->addFieldToTab(
                    'Root.Prices',
                    new \NumericField($currencyCode . "Price", $currencyCode . " Price")
                );

            }

        }

        return $fields;
    }
}
