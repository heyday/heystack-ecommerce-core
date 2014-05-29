<?php

namespace Heystack\Ecommerce\DependencyInjection;

use Heystack\Core\Loader\DBClosureLoader;
use Heystack\Ecommerce\Config\ContainerConfig;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyDataProvider;
use Heystack\Ecommerce\Locale\Interfaces\CountryDataProviderInterface;
use Heystack\Ecommerce\Locale\Interfaces\ZoneDataProviderInterface;
use Heystack\Ecommerce\Services;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
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
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Core
 */
class ContainerExtension extends Extension
{

    /**
     * Loads a specific configuration. Additionally calls processConfig, which handles overriding
     * the subsytem level configuration with more relevant mysite/config level configuration
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        (new YamlFileLoader(
            $container,
            new FileLocator(ECOMMERCE_CORE_BASE_PATH . '/config/')
        ))->load('services.yml');

        $config = (new Processor())->processConfiguration(
            new ContainerConfig(),
            $configs
        );

        if (isset($config['yml_transaction']) && $container->hasDefinition('transaction_schema')) {
            $definition = $container->getDefinition('transaction_schema');
            $definition->replaceArgument(0, $config['yml_transaction']);
        }

        // Configure currencies from DB
        if (isset($config['currency_db'])) {
            $handler = function (CurrencyDataProvider $record) use ($container) {
                $code = $record->getCurrencyCode();
                $definition = new Definition(
                    'Heystack\\Ecommerce\\Currency\\Currency',
                    [
                        $record->getCurrencyCode(),
                        $record->getValue(),
                        (bool) $default = $record->isDefaultCurrency()
                    ]
                );
                $definition->addTag(Services::CURRENCY_SERVICE . '.currency');
                $container->setDefinition(
                    "currency.$code",
                    $definition
                );
                if ($default) {
                    $definition->addTag(Services::CURRENCY_SERVICE . '.currency_default');
                }
            };

            $resource = call_user_func([$config['currency_db']['from'], 'get'])->where($config['currency_db']['where']);
            
            (new DBClosureLoader($handler))->load($resource);
            
        } elseif (isset($config['currency'])) {
            foreach ($config['currency'] as $currency) {
                $container->setDefinition(
                    "currency.{$currency['code']}",
                    $definition = new Definition(
                        'Heystack\\Ecommerce\\Currency\\Currency',
                        [
                            $currency['code'],
                            $currency['value'],
                            (bool) $currency['default']
                        ]
                    )
                );
                $definition->addTag(Services::CURRENCY_SERVICE . '.currency');
                if ($currency['default']) {
                    $definition->addTag(Services::CURRENCY_SERVICE . '.currency_default');
                }
            }
        }

        // Configure locale from DB
        if (isset($config['locale_db'])) {
            $resource = call_user_func([$config['locale_db']['from'], 'get'])->where($config['locale_db']['where']);
            
            $handler = function (CountryDataProviderInterface $record) use ($container) {
                $localeDefinition = new DefinitionDecorator(Services::LOCALE_SERVICE . '.country');
                $localeDefinition->addArgument($identifier = $record->getCountryCode());
                $localeDefinition->addArgument($record->getName());
                $localeDefinition->addArgument((bool) $default = $record->isDefault());
                $localeDefinition->addTag(Services::LOCALE_SERVICE . '.locale');
                
                $container->setDefinition(
                    "locale.$identifier",
                    $localeDefinition
                );

                if ($default) {
                    $localeDefinition->addTag(Services::LOCALE_SERVICE . '.locale_default');
                }
            };
            
            (new DBClosureLoader($handler))->load($resource);
            
        } elseif (isset($config['locale'])) {
            foreach ($config['locale'] as $locale) {
                $localeDefinition = new DefinitionDecorator(Services::LOCALE_SERVICE . '.country');
                $localeDefinition->addArgument($locale['code']);
                $localeDefinition->addArgument($locale['name']);
                $localeDefinition->addArgument((bool) $locale['default']);
                $localeDefinition->addTag(Services::LOCALE_SERVICE . '.locale');
                
                $container->setDefinition(
                    "locale.{$locale['code']}",
                    $localeDefinition
                );

                if ($locale['default']) {
                    $localeDefinition->addTag(Services::LOCALE_SERVICE . '.locale_default');
                }
            }
        }
        
        if (isset($config['zone_db'])) {
            $resource = call_user_func([$config['zone_db']['from'], 'get'])->where($config['zone_db']['where']);

            $handler = function (ZoneDataProviderInterface $record, $index) use ($container, $config) {
                $zoneDefinition = new DefinitionDecorator(Services::ZONE_SERVICE . '.zone');

                $zoneDefinition->addArgument($record->getName());
                $zoneDefinition->addArgument($record->getCountryCodes());
                $zoneDefinition->addTag(Services::ZONE_SERVICE . '.zone');

                $container->setDefinition(
                    sprintf("zone.%s.%s", $config['zone_db']['from'], $index),
                    $zoneDefinition
                );
            };

            (new DBClosureLoader($handler))->load($resource);
            
        } elseif (isset($config['zone'])) {
            foreach ($config['zone'] as $index => $locale) {
                $zoneDefinition = new DefinitionDecorator(Services::ZONE_SERVICE . '.zone');
                $zoneDefinition->addArgument($locale['name']);
                $zoneDefinition->addArgument($locale['countries']);
                $zoneDefinition->addTag(Services::ZONE_SERVICE . '.zone');
                
                $container->setDefinition(
                    sprintf("zone.%s", $index),
                    $zoneDefinition
                );
            }
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
