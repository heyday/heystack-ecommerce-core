<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;

use Heystack\Subsystem\Ecommerce\Purchasable\Interfaces\PurchasableHolderInterface;

use Heystack\Subsystem\Core\ViewableData\ViewableDataInterface;

use Heystack\Subsystem\Core\Exception\ConfigurationException;

use Heystack\Subsystem\Ecommerce\Currency\CurrencyService;

class Collator implements ViewableDataInterface
{

    protected $precision = 2;
    protected $transaction = null;
    protected $currencyService = null;

    public function __construct(TransactionInterface $transaction, CurrencyService $currencyService, $precision = null)
    {
        if (!is_null($precision)) {
            $this->setPrecision($precision);
        }

        $this->transaction = $transaction;
        $this->currencyService = $currencyService;

    }

    public function getCastings()
    {
        return [
            'Total' => 'Money',
            'SubTotal' => 'Money'
        ];

    }

    public function getDynamicMethods()
    {
        return [];
    }

    public function setPrecision($precision)
    {
        if (is_int($precision)) {

            $this->precision = $precision;

        } else {

            throw new ConfigurationException('Precision must be an integer');

        }

    }

    public function getTotal()
    {
        return [
            'Amount' => $this->round($this->transaction->getTotal()),
            'Currency' => $this->currencyService->getActiveCurrencyCode()
        ];
    }

    public function getSubTotal()
    {

        $modifiers = $this->transaction->getModifiersByType(TransactionModifierTypes::CHARGEABLE);

        foreach ($modifiers as $identifier => $modifier) {

            if (!$modifier instanceof PurchasableHolderInterface) {

                unset($modifiers[$identifier]);

            }

        }

        return [
            'Amount' => $this->round($this->sumModifiers($modifiers)),
            'Currency' => $this->currencyService->getActiveCurrencyCode()
        ];

    }

    protected function round($amount)
    {
        return round($amount, $this->precision);
    }

    protected function sumModifiers(array $modifiers)
    {

        $total = 0;

        foreach ($modifiers as $modifier) {

            $total += $modifier->getTotal();

        }

        return $total;

    }

}
