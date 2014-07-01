<?php

namespace Heystack\Ecommerce\Transaction;

use Heystack\Core\ViewableData\ViewableDataInterface;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;
use Heystack\Ecommerce\Currency\Traits\HasCurrencyServiceTrait;
use Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface;
use Heystack\Ecommerce\Locale\Traits\HasLocaleServiceTrait;
use Heystack\Ecommerce\Purchasable\Interfaces\PurchasableHolderInterface;
use Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface;
use Heystack\Ecommerce\Transaction\Traits\HasTransactionTrait;

/**
 * Class Collator
 * @package Heystack\Ecommerce\Transaction
 */
class Collator implements ViewableDataInterface
{
    use HasTransactionTrait;
    use HasCurrencyServiceTrait;
    use HasLocaleServiceTrait;

    /**
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionInterface $transaction
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface $currencyService
     * @param \Heystack\Ecommerce\Locale\Interfaces\LocaleServiceInterface $localeService
     */
    public function __construct(
        TransactionInterface $transaction,
        CurrencyServiceInterface $currencyService,
        LocaleServiceInterface $localeService
    )
    {
        $this->transaction = $transaction;
        $this->currencyService = $currencyService;
        $this->localeService = $localeService;
    }

    /**
     * @return array
     */
    public function getCastings()
    {
        return [
            'Total' => 'Heystack\Ecommerce\Currency\MoneyCasting',
            'SubTotal' => 'Heystack\Ecommerce\Currency\MoneyCasting'
        ];
    }

    /**
     * @return array
     */
    public function getDynamicMethods()
    {
        return [];
    }

    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getTotal()
    {
        return $this->transaction->getTotal();
    }

    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getSubTotal()
    {
        $modifiers = $this->transaction->getChargeableModifiers();

        foreach ($modifiers as $identifier => $modifier) {

            if (!$modifier instanceof PurchasableHolderInterface) {

                unset($modifiers[$identifier]);

            }

        }

        return $this->sumModifiers($modifiers);
    }

    /**
     * @param \Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface[] $modifiers
     * @return \SebastianBergmann\Money\Money
     */
    protected function sumModifiers(array $modifiers)
    {
        $total = $this->currencyService->getZeroMoney();

        foreach ($modifiers as $modifier) {
            $total = $total->add($modifier->getTotal());
        }

        return $total;
    }
}
