<?php

namespace Heystack\Subsystem\Ecommerce;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

use Heystack\Subsystem\Core\ContainerExtensionConfigProcessor;

class ContainerExtension extends ContainerExtensionConfigProcessor implements ExtensionInterface
{

    public function load(array $config, ContainerBuilder $container)
    {

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../../../config/')
        );

        $loader->load('services.yml');
        
        $this->processConfig($config, $container);
    }

    public function getNamespace()
    {
        return 'ecommerce';
    }

    public function getXsdValidationBasePath()
    {
        return false;
    }

    public function getAlias()
    {
        return 'ecommerce';
    }

}
