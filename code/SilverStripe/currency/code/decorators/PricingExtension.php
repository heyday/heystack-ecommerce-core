<?php

use Heystack\Subsystem\Core\ServiceStore;
use Heystack\Subsystem\Ecommerce\Currency\CurrencyService;

class PricingExtension extends DataExtension
{

    public static function get_extra_config($class, $extension, $args)
    {

        $db = array();

        $currencyService = ServiceStore::getService(\Heystack\Subsystem\Ecommerce\Services::CURRENCY_SERVICE);

        if ($currencyService instanceof CurrencyService) {

            foreach ($currencyService->getCurrencies() as $currency) {

                $db[$currency->getCurrencyCode()."Price"] = 'Decimal(10, 2)';

            }

        }

        return array(
            'db' => $db
        );

    }

    public function updateCMSFields(FieldList $fields)
    {

        $currencyService = ServiceStore::getService(\Heystack\Subsystem\Ecommerce\Services::CURRENCY_SERVICE);

        foreach ($currencyService->getCurrencies() as $currency) {

            $currencyCode = $currency->getCurrencyCode();

            $fields->removeByName($currencyCode . "Price");

            $fields->addFieldToTab('Root.Prices', new NumericField($currencyCode."Price", $currencyCode . " Price"));

        }

        return $fields;
    }

}