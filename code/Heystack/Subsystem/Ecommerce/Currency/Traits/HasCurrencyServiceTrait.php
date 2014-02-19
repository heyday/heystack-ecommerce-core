<?php

namespace Heystack\Subsystem\Ecommerce\Currency\Traits;

use Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;

trait HasCurrencyServiceTrait
{
    /**
     * @var \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface
     */
    protected $currencyService;

    /**
     * @param \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface $currencyService
     */
    public function setCurrencyService(CurrencyServiceInterface $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @return \Heystack\Subsystem\Ecommerce\Currency\Interfaces\CurrencyServiceInterface
     */
    public function getCurrencyService()
    {
        return $this->currencyService;
    }
} 