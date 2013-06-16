<?php

namespace Heystack\Subsystem\Ecommerce\Traits;

use Heystack\Subsystem\Core\ServiceStore;
use Heystack\Subsystem\Core\Services;

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

        $inputHandlerService = ServiceStore::getService(Services::INPUT_PROCESSOR_HANDLER);
        $outputHandlerService = ServiceStore::getService(Services::OUTPUT_PROCESSOR_HANDLER);

        $request = $this->getRequest();
        $identifier = $request->param('Processor');

        return $outputHandlerService->process(
                $identifier,
                $this,
                $inputHandlerService->process($identifier, $request)
        );

    }

}
