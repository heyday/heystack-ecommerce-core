<?php
/**
 * This file is part of the Heystack package
 *
 * @package Ecommerce-Core
 */

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

    use \Heystack\Subsystem\Ecommerce\Traits\OutputHandlerControllerTrait;

    public static $url_segment = 'ecommerce/input';

}
