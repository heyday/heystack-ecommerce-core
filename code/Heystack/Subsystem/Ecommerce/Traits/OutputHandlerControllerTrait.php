<?php

namespace Heystack\Subsystem\Ecommerce\Traits;

use Heystack\Subsystem\Core\ServiceStore;

trait OutputHandlerControllerTrait
{

    /**
     * Process the request to the controller and direct it to the correct input
     * and output controllers via the input and output processor services.
     *
     * @return mixed
     */
    public function process()
    {

        $inputHandlerService = ServiceStore::getService('input_processor_handler'); //TODO
        $outputHandlerService = ServiceStore::getService('output_processor_handler'); //TODO

        $request = $this->getRequest();
        $identifier = $request->param('Processor');

        return $outputHandlerService->process(
                $identifier,
                $this,
                $inputHandlerService->process($identifier, $request)
        );

    }

}
