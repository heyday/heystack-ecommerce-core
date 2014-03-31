<?php

namespace Heystack\Ecommerce\DependencyInjection\CompilerPass;

use Heystack\Core\DependencyInjection\CompilerPass\HasService;
use Heystack\Ecommerce\Services;

/**
 * Class HasCurrencyService
 * @package Heystack\Ecommerce\DependencyInjection\CompilerPass
 */
class HasCurrencyService extends HasService
{
    /**
     * The name of the service in the container
     * @return string
     */
    protected function getServiceName()
    {
        return Services::CURRENCY_SERVICE;
    }

    /**
     * The method name used to set the service
     * @return string
     */
    protected function getServiceSetterName()
    {
        return 'setCurrencyService';
    }
} 