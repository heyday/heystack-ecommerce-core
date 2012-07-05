<?php

use Heystack\Subsystem\Core\ServiceStore;

class EcommerceInputController extends Controller
{

    public static $url_segment = 'ecommerce/input';

    private $stateService;
    private $inputHandlerService;
    private $outputHandlerService;

    public function __construct()
    {

        parent::__construct();

        $this->stateService = ServiceStore::getService('state');
        $this->inputHandlerService = ServiceStore::getService('input_processor_handler');
        $this->outputHandlerService = ServiceStore::getService('output_processor_handler');

    }

    public function process()
    {

        $request = $this->getRequest();
        $identifier = $request->param('Processor');

        return $this->outputHandlerService->process(
                $identifier,
                $this,
                $this->inputHandlerService->process($identifier, $request)
        );

    }

}
