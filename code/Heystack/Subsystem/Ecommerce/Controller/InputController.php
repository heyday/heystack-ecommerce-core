<?php
namespace Heystack\Subsystem\Ecommerce\Controller;

use Heystack\Subsystem\Core\Input\Handler as InputHandler;
use Heystack\Subsystem\Core\Output\Handler as OutputHandler;

class InputController extends \Controller
{
    protected $inputHandler;
    protected $outputHandler;

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

        return $this->getOutputHandler()->process(
            $identifier,
            $this,
            $this->getInputHandler()->process($identifier, $request)
        );

    }

    public function setInputHandler(InputHandler $service)
    {
        $this->inputHandler = $service;
    }

    public function setOutputHandler(OutputHandler $service)
    {
        $this->outputHandler = $service;
    }

    protected function getInputHandler()
    {
        if ($this->inputHandler instanceof InputHandler) {
            return $this->inputHandler;
        }else{
            throw new \RuntimeException('The Heystack\Subsystem\Ecommerce\Controller\InputController requires Heystack\Subsystem\Core\Input\Handler to be set');
        }
    }

    protected function getOutputHandler()
    {
        if ($this->outputHandler instanceof OutputHandler) {
            return $this->outputHandler;
        }else{
            throw new \RuntimeException('The Heystack\Subsystem\Ecommerce\Controller\InputController requires Heystack\Subsystem\Core\Output\Handler to be set');
        }
    }
}