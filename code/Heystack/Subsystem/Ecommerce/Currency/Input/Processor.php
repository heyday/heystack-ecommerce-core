<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Input namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency\Input;

use Heystack\Subsystem\Core\Input\ProcessorInterface;
use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;

/**
 * Input Processor for Currency
 *
 * Handles all input related to Currency
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class Processor implements ProcessorInterface
{

    /**
     * Stores the classname of the currency data object
     * @var string 
     */
    private $currencyClass;
    /**
     * Stores the CurrencyService
     * @var Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface 
     */
    private $currencyService;

    /**
     * Currency Input Processor Constructor
     * @param type $currencyClass
     * @param \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface $currencyService
     */
    public function __construct($currencyClass, CurrencyServiceInterface $currencyService)
    {
        
        $this->currencyClass = $currencyClass;
        $this->currencyService = $currencyService;

    }

    /**
     * Returns the identifier for this object
     * @return string
     */
    public function getIdentifier()
    {
        return strtolower($this->currencyClass);

    }

    /**
     * Method to determine how to handle the request.
     * Uses the currency service to set the active currency
     * @param \SS_HTTPRequest $request
     * @return array
     */
    public function process(\SS_HTTPRequest $request)
    {

        if ($id = $request->param('ID')) {

            $currency = \DataObject::get_by_id($this->currencyClass, $id);

            if ($currency instanceof $this->currencyClass) {
                
                $this->currencyService->setCurrency($currency);

                return array(
                    'Success' => true
                );

            }

        }
        
        return array(
            'Success' => false
        );

    }

}
