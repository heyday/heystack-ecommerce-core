<?php

namespace Heystack\Ecommerce\Currency\Traits;

use Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;

/**
 * @package Heystack\Ecommerce\Currency\Traits
 */
trait HasCurrencyServiceTrait
{
    /**
     * @var \Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface
     */
    protected $currencyService;

    /**
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface $currencyService
     */
    public function setCurrencyService(CurrencyServiceInterface $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface
     */
    public function getCurrencyService()
    {
        return $this->currencyService;
    }
} 