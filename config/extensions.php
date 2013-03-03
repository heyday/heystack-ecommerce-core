<?php
use Camspiers\DependencyInjection\SharedContainerFactory;

SharedContainerFactory::addExtension(new Heystack\Subsystem\Ecommerce\DependencyInjection\ContainerExtension);

SharedContainerFactory::addCompilerPass(new Heystack\Subsystem\Ecommerce\DependencyInjection\CompilerPass\Transaction);
