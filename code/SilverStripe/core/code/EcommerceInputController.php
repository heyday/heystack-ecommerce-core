<?php
/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * Heystack\Subsystem\Core\Storage\DataObjectCodeGenerator namespace
 */
use Heystack\Subsystem\Core\ServiceStore;

/**
 * EcommerceInputController handles all input to the system.
 *
 * Calls which interact with the ecommerce system should be directed through
 * this controller.
 *
 * @copyright  Heyday
 * @author Stevie Mayhew <stevie@heyday.co.nz>
 * @author Cameron Spiers <cam@heyday.co.nz>
 * @package Heystack
 *
 */
class EcommerceInputController extends Controller
{

    public static $url_segment = 'ecommerce/input';

    private $stateService;
    private $inputHandlerService;
    private $outputHandlerService;

    /**
     * Setup this controller.
     */
    public function __construct()
    {

        parent::__construct();

        $this->stateService = ServiceStore::getService('state');
        $this->inputHandlerService = ServiceStore::getService('input_processor_handler');
        $this->outputHandlerService = ServiceStore::getService('output_processor_handler');

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

        return $this->outputHandlerService->process(
                $identifier,
                $this,
                $this->inputHandlerService->process($identifier, $request)
        );

    }

}
