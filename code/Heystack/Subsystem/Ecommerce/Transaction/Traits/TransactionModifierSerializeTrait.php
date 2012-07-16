<?php

namespace Heystack\Subsystem\Ecommerce\Transaction\Traits;

trait TransactionModifierSerializeTrait
{ 
    /**
     * Returns a serialized string from the data array
     * @return string
     */
    public function serialize()
    {
        return serialize($this->data);
    }

    /**
     * Unserializes the data into the data array
     * @param string $data
     */
    public function unserialize($data)
    {
        $this->data = unserialize($data);
    }
}
