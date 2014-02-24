<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * Output namespace
 */
namespace Heystack\Ecommerce\Currency\Output;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Output\ProcessorInterface;

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
     * @return \Heystack\Core\Identifier\Identifier
     */
    public function getIdentifier()
    {
        return new Identifier(
            strtolower($this->currencyClass)
        );
    }
    /**
     * Method used to determine how to handle the output based on the InputProcessor's result
     * @param  \Controller          $controller
     * @param  array|void           $result
     * @return \SS_HTTPResponse
     */
    public function process(\Controller $controller, $result = null)
    {
        if ($controller->getRequest()->isAjax()) {
            $response = $controller->getResponse();
            $response->setStatusCode(200);

            if (is_array($result)) {
                $response->addHeader('Content-Type', 'application/json');
                $response->setBody(json_encode($result));
            }

            return $response;
        } else {
            $controller->redirectBack();
        }

        return null;
    }
}
