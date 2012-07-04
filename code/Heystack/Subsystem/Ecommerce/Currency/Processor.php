<?php

namespace Heystack\Subsystem\Ecommerce\Currency;

use Heystack\Subsystem\Ecommerce\Currency\Events as CurrencyEvents;

use Heystack\Subsystem\Core\Input\ProcessorInterface;
use Heystack\Subsystem\Core\State\State;

use Symfony\Component\EventDispatcher\EventDispatcher;

class Processor implements ProcessorInterface
{

    private $state;
    private $eventDispatcher;

    public function __construct(State $state, EventDispatcher $eventDispatcher)
    {

        $this->state = $state;
        $this->eventDispatcher = $eventDispatcher;

    }

    public function getIdentifier()
    {
        return 'currency';

    }

    public function process(\SS_HTTPRequest $request)
    {

        $this->state->setByKey('currency', $request->getVar('currency'));

        $this->eventDispatcher->dispatch(CurrencyEvents::CURRENCY_CHANGE);

    }

}
