<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Ecommerce namespace
 */
namespace Heystack\Subsystem\Ecommerce;

/**
 * Holds constants corresponding to the services defined in the services.yml file
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 */
final class Services
{
    /**
     * Holds the identfier of the transaction
     * For use with the ServiceStore::getService($identifier) call
     */
    const TRANSACTION = 'transaction';

    /**
     * Holds the identifier of the transaction subscriber
     * For use with the ServiceStore::getService($identifier) call
     */
    const TRANSACTION_SUBSCRIBER = 'transaction_subscriber';

    /**
     * Holds the identifier of the currency service
     * For use with the ServiceStore::getService($identifier) call
     */
    const CURRENCY_SERVICE = 'currency_service';

    /**
     * Holds the identifier of the currency subscriber
     * For use with the ServiceStore::getService($identifier) call
     */
    const CURRENCY_SUBSCRIBER = 'currency_subscriber';

    /**
     * Holds the identifier of the currency input processor
     * For use with the ServiceStore::getService($identifier) call
     */
    const CURRENCY_INPUT_PROCESSOR = 'currency_input_processor';

    /**
     * Holds the identifier of the currency output processor
     * For use with the ServiceStore::getService($identifier) call
     */
    const CURRENCY_OUTPUT_PROCESSOR = 'currency_output_processor';

    /**
     * Holds the identifier of the locale service
     * For use with the ServiceStore::getService($identifier) call
     */
    const LOCALE_SERVICE = 'locale_service';
}
