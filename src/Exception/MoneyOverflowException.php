<?php

namespace Heystack\Ecommerce\Exception;

/**
 * Class MoneyOverflowException
 * @package Heystack\Ecommerce\Exception
 */
class MoneyOverflowException extends \OverflowException
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct("Money bounds have been exceeded");
    }
} 