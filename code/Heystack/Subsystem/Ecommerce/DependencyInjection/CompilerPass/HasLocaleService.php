<?php

namespace Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass;

use Heystack\Subsystem\Ecommerce\Services;
use Heystack\Subsystem\Core\DependencyInjection\CompilerPass\HasService;

/**
 * Class HasLocaleService
 * @package Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass
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