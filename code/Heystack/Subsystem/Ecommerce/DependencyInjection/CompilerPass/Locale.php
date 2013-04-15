<?php

/**
 * This file is part of the Heystack package
 *
 * @package Heystack
 */

/**
 * CompilerPass namespace
 */
namespace Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

use Heystack\Subsystem\Ecommerce\Services;

/**
 * @copyright  Heyday
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package    Heystack
 */
class Locale implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(Services::LOCALE_SERVICE)) {
            return;
        }

        $locales = array();

        foreach ($container->findTaggedServiceIds(Services::LOCALE_SERVICE . '.locale') as $id => $tags) {
            foreach ($tags as $tag) {
                $locales[] = new Reference($id);
            }
        }

        if (count($locales) == 0) {
            throw new \RuntimeException('At least one locale must be configured');
        }

        $defaultLocale = reset($locales);

        foreach ($container->findTaggedServiceIds(Services::LOCALE_SERVICE . '.locale_default') as $id => $tags) {
            foreach ($tags as $tag) {
                $defaultLocale = new Reference($id);
            }
        }

        $container->getDefinition(Services::LOCALE_SERVICE)->replaceArgument(0, $locales);
        $container->getDefinition(Services::LOCALE_SERVICE)->replaceArgument(1, $defaultLocale);

    }
}
