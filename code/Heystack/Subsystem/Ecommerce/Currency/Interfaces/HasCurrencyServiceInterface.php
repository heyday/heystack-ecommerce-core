<?php

namespace Heystack\Subsystem\Ecommerce\Currency\Interfaces;

/**
 * Interface HasCurrencyServiceInterface
 * @package Heystack\Subsystem\Ecommerce\Currency\Interfaces
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