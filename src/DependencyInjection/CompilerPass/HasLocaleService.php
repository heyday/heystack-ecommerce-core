<?php

namespace Heystack\Ecommerce\DependencyInjection\CompilerPass;

use Heystack\Core\DependencyInjection\CompilerPass\HasService;
use Heystack\Ecommerce\Services;

/**
 * Class HasLocaleService
 * @package Heystack\Ecommerce\DependencyInjection\CompilerPass
 */
class HasLocaleService extends HasService
{
    /**
     * The name of the service in the container
     * @return string
     */
    protected function getServiceName()
    {
        return Services::LOCALE_SERVICE;
    }

    /**
     * The method name used to set the service
     * @return string
     */
    protected function getServiceSetterName()
    {
        return 'setLocaleService';
    }
} 