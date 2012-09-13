<?php

namespace Heystack\Subsystem\Ecommerce\Transaction;

use Heystack\Subsystem\Ecommerce\Transaction\Interfaces\TransactionInterface;

use Heystack\Subsystem\Ecommerce\Purchasable\Interfaces\PurchasableHolderInterface;

use Heystack\Subsystem\Core\ViewableData\ViewableDataInterface;

use Heystack\Subsystem\Core\Exception\ConfigurationException;

class Collator implements ViewableDataInterface
{

    protected $precision = 2;
    protected $transaction = null;

    public function __construct(TransactionInterface $transaction, $precision = null)
    {
        if (!is_null($precision)) {
            $this->setPrecision($precision);
        }

        $this->transaction = $transaction;

    }

    public function getCastings()
    {

        return array(
            'Total' => 'Money',
            'SubTotal' => 'Money'
        );

    }

    public function getDynamicMethods()
    {
        return array();
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
        return array(
            'Amount' => $this->round($this->transaction->getTotal()),
            'Currency' => $this->transaction->getCurrencyCode()
        );
    }

    public function getSubTotal()
    {

        $modifiers = $this->transaction->getModifiersByType(TransactionModifierTypes::CHARGEABLE);

        foreach ($modifiers as $identifier => $modifier) {

            if (!$modifier instanceof PurchasableHolderInterface) {

                unset($modifiers[$identifier]);

            }

        }
        
        return array(
            'Amount' => $this->round($this->sumModifiers($modifiers)),
            'Currency' => $this->transaction->getCurrencyCode()
        );

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
