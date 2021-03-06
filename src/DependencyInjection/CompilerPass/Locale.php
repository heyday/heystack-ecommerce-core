<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * CompilerPass namespace
 */
namespace Heystack\Ecommerce\DependencyInjection\CompilerPass;

use Heystack\Ecommerce\Services;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package    Heystack
 */
class Locale implements CompilerPassInterface
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @throws \RuntimeException
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::LOCALE_SERVICE)) {
            return;
        }

        $locales = [];

        foreach ($container->findTaggedServiceIds(Services::LOCALE_SERVICE . '.locale') as $id => $_) {
            $locales[] = new Reference($id);
        }

        if (count($locales) == 0) {
            throw new \RuntimeException('At least one locale must be configured');
        }

        $defaultLocale = reset($locales);

        foreach ($container->findTaggedServiceIds(Services::LOCALE_SERVICE . '.locale_default') as $id => $_) {
            $defaultLocale = new Reference($id);
        }

        $container->getDefinition(Services::LOCALE_SERVICE)->replaceArgument(0, $locales);
        $container->getDefinition(Services::LOCALE_SERVICE)->replaceArgument(1, $defaultLocale);

        $zoneService = $container->getDefinition(Services::ZONE_SERVICE);

        foreach ($container->findTaggedServiceIds(Services::ZONE_SERVICE . '.zone') as $id => $_) {
            $zoneService->addMethodCall('addZone', [new Reference($id)]);
        }
    }
}
