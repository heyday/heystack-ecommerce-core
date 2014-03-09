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
     * @param InputHandler $inputHandler
     * @param OutputHandler $outputHandler
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
     * @return mixed
     */
    public function process()
    {
        $request = $this->getRequest();
        $identifier = $request->param('Processor');

        return $this->outputHandler->process(
            $identifier,
            $this,
            $this->inputHandler->process($identifier, $request)
        );
    }

    /**
     * @param InputHandler $service
     */
    public function setInputHandler(InputHandler $service)
    {
        $this->inputHandler = $service;
    }

    /**
     * @param OutputHandler $service
     */
    public function setOutputHandler(OutputHandler $service)
    {
        $this->outputHandler = $service;
    }

    /**
     * @return InputHandler
     * @throws \RuntimeException
     */
    protected function getInputHandler()
    {
        return $this->inputHandler;
    }

    /**
     * @return OutputHandler
     * @throws \RuntimeException
     */
    protected function getOutputHandler()
    {
        return $this->outputHandler;
    }
}