<?php

namespace Heystack\Ecommerce\Currency;

use Heystack\Core\EventDispatcher;
use Heystack\Core\Identifier\IdentifierInterface;
use Heystack\Core\State\State;
use Heystack\Core\Traits\HasEventServiceTrait;
use Heystack\Core\Traits\HasStateServiceTrait;
use Heystack\Ecommerce\Currency\Event\CurrencyEvent;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface;
use Heystack\Ecommerce\Currency\Interfaces\CurrencyServiceInterface;
use Heystack\Ecommerce\Transaction\Events as TransactionEvents;
use SebastianBergmann\Money\Money;

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
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface $defaultCurrency
     * @param \Heystack\Core\State\State $stateService
     * @param \Heystack\Core\EventDispatcher $eventService
     */
    public function __construct(
        array $currencies,
        CurrencyInterface $defaultCurrency,
        State $stateService,
        EventDispatcher $eventService
    )
    {
        $this->setCurrencies($currencies);
        $this->defaultCurrency = $this->activeCurrency = $defaultCurrency;
        $this->stateService = $stateService;
        $this->eventService = $eventService;
    }

    /**
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface[] $currencies
     * @return void
     */
    protected function setCurrencies(array $currencies)
    {
        foreach ($currencies as $currency) {
            $this->addCurrency($currency);
        }
    }

    /**
     * @param \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface $currency
     * @return void
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
     * @return void
     */
    public function restoreState()
    {
        if ($activeCurrency = $this->stateService->getByKey(self::ACTIVE_CURRENCY_KEY)) {
            $this->activeCurrency = $activeCurrency;
        }
    }

    /**
     * Saves the data array on the State service
     * @return void
     */
    public function saveState()
    {
        $this->stateService->setByKey(
            self::ACTIVE_CURRENCY_KEY,
            $this->activeCurrency
        );
    }

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return bool true on success false on failure
     */
    public function setActiveCurrency(IdentifierInterface $identifier)
    {
        $currency = $this->getCurrency($identifier);
        if ($currency && $currency != $this->activeCurrency) {
            $this->activeCurrency = $currency;

            $this->saveState();

            $this->eventService->dispatch(
                Events::CHANGED,
                new CurrencyEvent(
                    $currency
                )
            );

            $this->eventService->dispatch(TransactionEvents::UPDATE);

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

        return $amount->multiply($toCurrency->getValue() / $fromCurrency->getValue());
    }

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
     * @return \Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface|null
     */
    public function getCurrency(IdentifierInterface $identifier)
    {
        return $this->hasCurrency($identifier) ? $this->currencies[$identifier->getFull()] : null;
    }

    /**
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
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
     * @param \Heystack\Core\Identifier\IdentifierInterface $identifier
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
