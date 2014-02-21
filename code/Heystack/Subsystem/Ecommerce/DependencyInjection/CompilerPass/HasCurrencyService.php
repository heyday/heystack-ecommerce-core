<?php

namespace Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass;

use Heystack\Subsystem\Ecommerce\Services;
use Heystack\Subsystem\Core\DependencyInjection\CompilerPass\HasService;

/**
 * Class HasCurrencyService
 * @package Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass
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