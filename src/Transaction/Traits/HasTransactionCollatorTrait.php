<?php

namespace Heystack\Ecommerce\Transaction\Traits;

use Heystack\Ecommerce\Transaction\Collator;

/**
 * Class HasTransactionCollatorTrait
 * @package Heystack\Ecommerce\Transaction\Traits
 */
trait HasTransactionCollatorTrait
{
    /**
     * @var \Heystack\Ecommerce\Transaction\Collator
     */
    protected $transactionCollator;

    /**
     * @param \Heystack\Ecommerce\Transaction\Collator $transactionCollator
     */
    public function setTransactionCollator(Collator $transactionCollator)
    {
        $this->transactionCollator = $transactionCollator;
    }

    /**
     * @return \Heystack\Ecommerce\Transaction\Collator
     */
    public function getTransactionCollator()
    {
        return $this->transactionCollator;
    }
} 