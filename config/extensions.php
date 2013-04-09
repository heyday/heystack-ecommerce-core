<?php

use Camspiers\DependencyInjection\SharedContainerFactory;
use Heystack\Subsystem\Ecommerce\DependencyInjection;

SharedContainerFactory::addExtension(new DependencyInjection\ContainerExtension());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\Transaction());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\Currency());
SharedContainerFactory::addCompilerPass(new DependencyInjection\CompilerPass\Locale());
