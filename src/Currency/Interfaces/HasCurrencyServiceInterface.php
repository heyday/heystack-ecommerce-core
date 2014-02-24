<?php

namespace Heystack\Ecommerce\Currency\Interfaces;

/**
 * Interface HasCurrencyServiceInterface
 * @package Heystack\Ecommerce\Currency\Interfaces
 */
interface HasCurrencyServiceInterface
{
    /**
     * @return mixed
     */
    public function getCurrencyService();

    /**
     * @param CurrencyServiceInterface $currencyService
     * @return mixed
     */
    public function setCurrencyService(CurrencyServiceInterface $currencyService);
} 