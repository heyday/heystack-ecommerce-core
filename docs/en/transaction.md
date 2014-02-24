#Transaction Service

The Transaction Service forms the heart of the ecommerce system. It represents an 'order' and ties all the information related to the order together.

There are two main bits of information that the Transaction keeps track of. Those are the total and the status.

There is only one Transaction active for each user session at a time. Once a transaction is completed and stored a new transaction is created. Actually, the state is cleared of all transaction related information and that is how we reset the Transaction service.

The Transaction Service implements the stateable interface (`Heystack\Core\State\StateableInterface`) from Heystack in order to keep its state between requests. It also implements the storable interface (`Heystack\Core\Storage\StorableInterface`) from the Heystack so that once the transaction is complete it can be stored, usually on the database.

## Transaction Events `Heystack\Ecommerce\Transaction\Events` 

The Transaction Service only updates itself when reacting to events. Usually a transaction modifier dispatches an 'Update' event which the transaction subscriber picks up that then gets the transaction to update itself. Once the transaction has finished updating it then dispatches an 'Updated' event so that other parts of the system can react to the update if needed.

Another event that that the transaction reacts to is the 'store' event. This signals to the transaction that it needs to use the Storage Service to save its data to a datastore (database or otherwise). Once this is done, a 'Stored' event is dispatched to let the other parts of the system know and react to the event.

## Transaction Subscriber `Heystack\Ecommerce\Transaction\Subscriber`

The transaction subscriber controls the transaction based on the events that it has subscribed to. This is where all the action happens when the 'update' event is dispatched from a transaction modifier telling the transaction to update itself. (e.g. A new product has been added to the cart; The user has changed the currency)

## Transaction Modifiers `Heystack\Ecommerce\Transaction\Interfaces\TransactionModifierInterface`

All transaction modifiers are added through Dependency Injection. Any subsystem that affect the 'total' of the transaction are added as a transaction modifier except for the Currency Service. The Purchasable Holder is a prime example of a Transaction Modifier. It stores all the products that were put in the cart by the end-user and sums up the total price of the items.

### Transaction Modifier Types `Heystack\Ecommerce\Transaction\TransactionModifierTypes`

The transaction calculates the total based on transaction modifiers. It gets the totals from each transaction modifier and adds or subtracts based on the 'type' of that transaction modifier.

The 'Neutral' transaction modifier type is less common but still very useful if you require to save information related to the transaction but does not affect the 'total'.

1. Chargeable
2. Deductible
3. Neutral

## Transaction Collator `Heystack\Ecommerce\Transaction\Collator`

As mentioned above the Transaction Service ties together all information about the order. The transaction collator simplifies the accessibility of the data for use on SilverStripe templates. 

The transaction collator needs to be fed into a ViewableDataFormatter (`\ViewableDataFormatter`) before it can be used on the template:

```
public function Transaction()
{
    return new ViewableDataFormatter($this->transaction->getCollator());
}
```

You can use the collator to retrieve the transaction total in the Money format, you can customise the collator so you can use type cast the values into any format that would be most useful on the template. You can also add more types of information gathered from the transaction modifiers onto the collator.

Example of use in a template:

```
<% if Transaction %>
	<span class="transaction-total">$Transaction.Total.Nice</span>
<% end_if %>
```

Once customised, you can use Dependency Injection so that your customised collator is the one utilised by the system in the transaction.

Example of customised collator:

```
namespace Example;

use Heystack\Ecommerce\Purchasable\Interfaces\PurchasableHolderInterface;
use Heystack\Ecommerce\Transaction\TransactionModifierTypes;
use Heystack\Ecommerce\Transaction\Collator;

class TransactionCollator extends Collator
{
    public function getCastings()
    {
        return array_merge(parent::getCastings(),array(
            'TaxTotal' => 'Money'
        ));
    }

    public function getTaxTotal()
    {
        $taxHandler = $this->transaction->getModifier(TaxHandler::IDENTIFIER);

        return array(
            'Amount' => $this->round($taxHandler->getTotal()),
            'Currency' => $this->currencyService->getActiveCurrencyCode()
        );
    }

}

```

Example configuration to Dependency Inject collator (`/mysite/config/services.yml`):

```
parameters:
  collator.class: \Example\TransactionCollator
```