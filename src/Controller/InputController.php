<?php

namespace Heystack\Ecommerce\Controller;

use Heystack\Core\Input\Handler as InputHandler;
use Heystack\Core\Output\Handler as OutputHandler;

/**
 * Class InputController
 * @package Heystack\Ecommerce\Controller
 */
class InputController extends \Controller
{
    /**
     * @var \Heystack\Core\Input\Handler
     */
    protected $inputHandler;
    /**
     * @var \Heystack\Core\Output\Handler
     */
    protected $outputHandler;

    /**
     * @param \Heystack\Core\Input\Handler $inputHandler
     * @param \Heystack\Core\Output\Handler $outputHandler
     */
    public function __construct(InputHandler $inputHandler, OutputHandler $outputHandler)
    {
        $this->inputHandler = $inputHandler;
        $this->outputHandler = $outputHandler;
        parent::__construct();
    }

    /**
     * Process the request to the controller and direct it to the correct input
     * and output controllers via the input and output processor services.
     *
     * @param \SS_HTTPRequest $request
     * @return mixed|null
     */
    public function process(\SS_HTTPRequest $request)
    {
        $identifier = $request->param('Processor');

        return $this->outputHandler->process(
            $identifier,
            $this,
            $this->inputHandler->process($identifier, $request)
        );
    }

    /**
     * @return \Heystack\Core\Input\Handler
     */
    public function getInputHandler()
    {
        return $this->inputHandler;
    }

    /**
     * @return \Heystack\Core\Output\Handler
     */
    public function getOutputHandler()
    {
        return $this->outputHandler;
    }
}