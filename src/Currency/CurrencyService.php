<?php
/**
 * This file is part of the Ecommerce-Products package
 *
 * @package Ecommerce-Core
 */

/**
 * Currency namespace
 */
namespace Heystack\Ecommerce\Currency;

use Heystack\Core\Identifier\Identifier;
use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Core\State\State;
use Heystack\Core\Traits\HasEventServiceTrait;
use Heystack\Core\Traits\HasStateServiceTrait;
use Heystack\Ecommerce\Currency\Event\CurrencyEvent;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;
use SebastianBergmann\Money\Money;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * CurrencyService default implementation
 *
 * This handles all the operations needed keep track of and calculate currencies and their values
 *
 * @copyright  Heyday
 * @author Glenn Bautista <glenn@heyday.co.nz>
 * @author Cam Spiers <cameron@heyday.co.nz>
 * @package Ecommerce-Core
 *
 */
class CurrencyService implements CurrencyServiceInterface
{
    use HasEventServiceTrait;
    use HasStateServiceTrait;
    /**
     * The key used on the data array to store the active currency
     */
    const ACTIVE_CURRENCY_KEY = 'currencyservice.activecurrency';
    /**
     * An array of currencies
     * @var \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface[]
     */
    protected $currencies;
    /**
     * @var \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    protected $activeCurrency;
    /**
     * @var \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    protected $defaultCurrency;

    /**
     * @param array $currencies
     * @param CurrencyInterface $defaultCurrency
     * @param State $stateService
     * @param EventDispatcherInterface $eventService
     */
    public function __construct(
        array $currencies,
        CurrencyInterface $defaultCurrency,
        State $stateService,
        EventDispatcherInterface $eventService
    ) {
        $this->setCurrencies($currencies);
        $this->defaultCurrency = $this->activeCurrency = $defaultCurrency;
        $this->stateService = $stateService;
        $this->eventService = $eventService;
    }
    /**
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface[] $currencies
     */
    protected function setCurrencies(array $currencies)
    {
        foreach ($currencies as $currency) {
            $this->addCurrency($currency);
        }
    }
    /**
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface $currency
     */
    protected function addCurrency(CurrencyInterface $currency)
    {
        $this->currencies[$currency->getIdentifier()->getFull()] = $currency;
    }
    /**
     * Uses the State service to retrieve the active currency's identifier and sets the active currency.
     *
     * If the retrieved identifier is not an instance of the Identifier Interface, then it checks if it is a string,
     * which it uses to create a new Identifier object to set the active currency.
     */
    public function restoreState()
    {
        if ($identifier = $this->stateService->getByKey(self::ACTIVE_CURRENCY_KEY)) {
            $this->setActiveCurrency(new Identifier($identifier), false);
        }
    }
    /**
     * Saves the data array on the State service
     */
    public function saveState()
    {
        $this->stateService->setByKey(
            self::ACTIVE_CURRENCY_KEY,
            $this->activeCurrency->getIdentifier()->getFull()
        );
    }
    /**
     * @param IdentifierInterface $identifier
     * @param bool $saveState Determines whether the state is saved and the update event is dispatched
     * @return bool true on success false on failure
     */
    public function setActiveCurrency(IdentifierInterface $identifier, $saveState = true)
    {
        $currency = $this->getCurrency($identifier);
        if ($currency && $currency != $this->activeCurrency) {
            $this->activeCurrency = $currency;

            if ($saveState) {
                $this->saveState();

                $this->eventService->dispatch(
                    Events::CHANGED,
                    new CurrencyEvent(
                        $currency
                    )
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Retrieves the currently active currency
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getActiveCurrency()
    {
        return $this->activeCurrency;
    }

    /**
     * Retrieves the currently active currency code
     * @return string
     */
    public function getActiveCurrencyCode()
    {
        return $this->getActiveCurrency()->getCurrencyCode();
    }

    /**
     * Retrieves all the available currencies
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface[]
     */
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * Converts amount from one currency to another using the currency's identifier
     * 
     * Warning this method can lose precision!
     * 
     * @param  \SebastianBergmann\Money\Money $amount
     * @param  \Heystack\Core\Identifier\IdentifierInterface $to
     * @return \SebastianBergmann\Money\Money
     * @throws \InvalidArgumentException
     */
    public function convert(Money $amount, IdentifierInterface $to)
    {   
        if (!$toCurrency = $this->getCurrency($to)) {
            throw new \InvalidArgumentException("Currency not supported");
        }
        
        /** @var \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface $fromCurrency */
        $fromCurrency = $amount->getCurrency();
        
        return $amount->multiply($fromCurrency->getValue() / $toCurrency->getValue());
    }

    /**
     * @param IdentifierInterface $identifier
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface|null
     */
    public function getCurrency(IdentifierInterface $identifier)
    {
        return $this->hasCurrency($identifier) ? $this->currencies[$identifier->getFull()] : null;
    }

    /**
     * @param IdentifierInterface $identifier
     * @return bool
     */
    public function hasCurrency(IdentifierInterface $identifier)
    {
        return isset($this->currencies[$identifier->getFull()]);
    }

    /**
     * Returns the default currency object
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface
     */
    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    /**
     * Sets the default currency
     * @param IdentifierInterface $identifier
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setDefaultCurrency(IdentifierInterface $identifier)
    {
        if (!$currency = $this->getCurrency($identifier)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Currency '%s' not supported",
                    $identifier
                )
            );
        }

        $this->defaultCurrency = $currency;
    }

    /**
     * @return \SebastianBergmann\Money\Money
     */
    public function getZeroMoney()
    {
        return new Money(0, $this->activeCurrency);
    }
}
