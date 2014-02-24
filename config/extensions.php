<?php

use Camspiers\DependencyInjection\SharedContainerFactory;
use Heystack\Ecommerce\DependencyInjection\ContainerExtension;
use Heystack\Ecommerce\DependencyInjection\CompilerPass;

SharedContainerFactory::addExtension(new ContainerExtension());
SharedContainerFactory::addCompilerPass(new CompilerPass\Transaction());
SharedContainerFactory::addCompilerPass(new CompilerPass\Currency());
SharedContainerFactory::addCompilerPass(new CompilerPass\Locale());
SharedContainerFactory::addCompilerPass(new CompilerPass\HasTransactionService());
SharedContainerFactory::addCompilerPass(new CompilerPass\HasCurrencyService());
SharedContainerFactory::addCompilerPass(new CompilerPass\HasLocaleService());
