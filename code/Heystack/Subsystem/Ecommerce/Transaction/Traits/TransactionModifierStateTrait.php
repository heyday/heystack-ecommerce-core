<?php

namespace Heystack\Subsystem\Ecommerce\Transaction\Traits;

trait TransactionModifierStateTrait
{ 
    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {
        $this->stateService->setObj(self::STATE_KEY, $this->data);
    }
    
    /**
     * Uses the State service to restore the data array
     */
    public function restoreState()
    {
        $this->data = $this->stateService->getObj(self::STATE_KEY);
    }
}