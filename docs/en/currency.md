# Currency Service

The Currency Service maintains a list of available currencies and keeps track of the currency that is in use in the application.

It uses the State Service provided by the Heystack to keep track of actively used currency.

The currencies available are added to the service on runtime via Dependency Injection. It does not matter in terms of performance whichever method you use for populating the currencies list.

Whenever the active currency is changed an event is dispatched so that the other parts of the system is made aware of the change and can act upon it if necessary. 

## Currency Population with DataObjects

One way to populate the Currency Service with currency objects is via the use of SilverStripe's DataObjects.

You must first define the currency dataobject:

```
use Heystack\Ecommerce\Currency\Interfaces\CurrencyInterface;
use Heystack\Ecommerce\Currency\Traits\CurrencyTrait;

class Currency extends DataObject implements CurrencyInterface{

	use CurrencyTrait;

    public static $db = array(
        'CurrencyCode' => 'Varchar(255)',
        'Value' => 'Decimal(10,3)',
        'Symbol' => 'Varchar(255)',
        'IsDefaultCurrency' => 'Boolean'
    );

    public static $summary_fields = array(
        'CurrencyCode',
        'Symbol',
        'Value',
        'IsDefaultCurrency'
    );

    public static $singular_name = "Currency";
    public static $plural_name = "Currencies";

}
```

You can see that we've implemented the CurrencyInterface using the CurrencyTrait, which is all that you'll need to do in most implementations unless you want to customise further.

After doing a /dev/build and adding a few currencies in the database you can use the stored data objects to populate your Currency Service. All you need to do is to configure the services.yml file in the config folder on your my site directory. (`/mysite/config/services.yml`)

Example:

```
ecommerce:
  currency_db:
    from: Currency
```

This snippet of yml tells the dependency injector to retrieve 'Currency' data objects from the database and add each instance into the list of currencies available to our Currency Service.

You will need to regenerate the container in order to actuate the additions.

Command to regenerate the container: `heystack\bin\heystack generate-container`

## Currency Population with Yaml

Another way to populate the Currency Service with Currency objects is by directly defining the currencies you want in the yaml configuration for your installation. (`/mysite/config/services.yml`)

This is probably best if you want only a limited number of currencies that you don't want the CMS user to be able to alter and add to.

Example:

```
ecommerce:
  currency:
    - code: NZD
      value: 1
      symbol: $
      default: true
    - code: AUD
      value: 1.2
      symbol: $
      default: false
```

This will add two currencies the New Zealand Dollar and the Australian Dollar once the container is regenerated.

Command to regenerate the container: `heystack/bin/heystack generate-container`

The Currency class used for the Currency objects as populated this way is at `Heystack\Ecommerce\Currency\Currency`

## Input/Output Processor

As part of the ecommerce-core module an ecommerce input controller is defined. This is a SilverStripe controller that passes on requests to various different input processors in the module. 

The Currency subsystem has an input/output processor pair that handles end-user input regarding changing the active currency. It accepts the currency code for it's input. The url is: `/ecommerce/input/currency/CURRENCYCODE`

The output processor returns a 'success' message if it the request was in ajax otherwise it redirects the user back to the page he/she came from.

Please note that it is optional to use the input/output processors. You can directly manipulate the Currency Service to change the actively used currency.