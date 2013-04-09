<?php
/**
 * This file is part of the Ecommerce-Core package
 *
 * @package Ecommerce-Core
 */

/**
 * DependencyInjection namespace
 */
namespace Heystack\Subsystem\Ecommerce\DependencyInjection;

use Heystack\Subsystem\Ecommerce\Config\ContainerConfig;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Dependency Injection Extension
 *
 * This class is responsible for loading this Subsystem's services.yml configuration
 * as well as overriding that configuration with the relevant entry from
 * the mysite/config/services.yml configuration file
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cameron Spiers <cam@heyday.co.nz>
 * @package Ecommerce-Core
 */
class ContainerExtension extends Extension
{

    /**
     * Loads a specific configuration. Additionally calls processConfig, which handles overriding
     * the subsytem level configuration with more relevant mysite/config level configuration
     *
     * @param array            $configs    An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(ECOMMERCE_CORE_BASE_PATH . '/config/')
        );

        $loader->load('services.yml');

        $config = (new Processor())->processConfiguration(
            new ContainerConfig(),
            $configs
        );

        $container->setParameter('currency.default.code', $config['currency']['default_code']);
        $container->setParameter('currency.default.value', $config['currency']['default_value']);
        $container->setParameter('country.default.code', $config['country']['default_code']);
        $container->setParameter('country.default.name', $config['country']['default_name']);
        
        if (isset($config['yml_transaction']) && $container->hasDefinition('transaction_schema')) {
            
            $definition = $container->getDefinition('transaction_schema');
            
            $definition->replaceArgument(0, $config['yml_transaction']);
            
        }
    }

    /**
     * Returns the namespace to be used for this extension (XML namespace).
     *
     * @return string The XML namespace
     *
     * @api
     */
    public function getNamespace()
    {
        return 'ecommerce';
    }

    /**
     * Returns the base path for the XSD files.
     *
     * @return string The XSD base path
     *
     * @api
     */
    public function getXsdValidationBasePath()
    {
        return false;
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     *
     * @api
     */
    public function getAlias()
    {
        return 'ecommerce';
    }

}
