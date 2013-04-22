<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Output namespace
 */
namespace Heystack\Subsystem\Ecommerce\Currency\Output;

use Heystack\Subsystem\Core\Identifier\Identifier;
use Heystack\Subsystem\Core\Output\ProcessorInterface;

/**
 * Output Processor for Currency
 *
 * Handles all output related to Currency
 *
 * @copyright  Heyday
 * @author     Glenn Bautista <glenn@heyday.co.nz>
 * @package    Ecommerce-Core
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
     * Currency Input Processor Constructor
     * @param string $currencyClass
     */
    public function __construct($currencyClass)
    {
        $this->currencyClass = $currencyClass;
    }
    /**
     * Returns the identifier for this object
     * @return \Heystack\Subsystem\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier(
            strtolower($this->currencyClass)
        );
    }
    /**
     * Method used to determine how to handle the output based on the InputProcessor's result
     * @param  \Controller $controller
     * @param  type        $result
     * @return SS_HTTPResponse
     */
    public function process(\Controller $controller, $result = null)
    {
        if ($controller->isAjax()) {

            $response = $controller->getResponse();
            $response->setStatusCode(200);
            $response->addHeader('Content-Type', 'application/json');

            $response->setBody(json_encode($result));

            return $response;
        } else {
            $controller->redirectBack();
        }

        return null;
    }
}
