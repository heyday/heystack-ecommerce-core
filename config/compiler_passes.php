<?php

use Heystack\Ecommerce\DependencyInjection\CompilerPass;

return [
    new CompilerPass\Transaction(),
    new CompilerPass\Currency(),
    new CompilerPass\Locale(),
    new CompilerPass\HasTransactionService(),
    new CompilerPass\HasCurrencyService(),
    new CompilerPass\HasLocaleService()
];