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
use Heystack\Subsystem\Core\Identifier\Identifier;

/**
 * Input Processor for Currency
 *
 * Handles all input related to Currency
 *
 * @copyright  Heyday
 * @author     Glenn Bautista <glenn@heyday.co.nz>
 * @package    Ecommerce-Core
 *
 */
class Processor implements ProcessorInterface
{
    /**
     * Stores the identifier for this object
     * @var string
     */
    private $identifier;
    /**
     * Stores the CurrencyService
     * @var Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface
     */
    private $currencyService;
    /**
     * Currency Input Processor Constructor
     * @param                          $identifier
     * @param CurrencyServiceInterface $currencyService
     */
    public function __construct($identifier, CurrencyServiceInterface $currencyService)
    {
        $this->identifier = $identifier;
        $this->currencyService = $currencyService;
    }
    /**
     * Returns the identifier for this object
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier(
            strtolower($this->identifier)
        );
    }
    /**
     * Method to determine how to handle the request.
     * Uses the currency service to set the active currency
     * @param  \SS_HTTPRequest $request
     * @return array
     */
    public function process(\SS_HTTPRequest $request)
    {
        if ($identifier = $request->param('ID')) {

            if ($this->currencyService->setActiveCurrency($identifier)) {
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
