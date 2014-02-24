# Locale Service
The Locale Service maintains a list of available locales and keeps track of the locale (country) that is in use in the application.

A locale is a representation of the location or country that the end-user is accessing the application from. It is useful when the application needs to be location aware.

It uses the State Service provided by the Heystack to keep track of actively used locale.

The locales available are added to the service on runtime via Dependency Injection. It does not matter in terms of performance whichever method you use for populating the locales list.

Whenever the active locale is changed an event is dispatched so that the other parts of the system is made aware of the change and can act upon it if necessary. 

## Locale Population with DataObjects

One way to populate the Locale Service with locale/country objects is through the use of SilverStripe's DataObjects.

You must first define the country data object:

```
use Heystack\Ecommerce\Locale\Traits\CountryTrait;
use Heystack\Ecommerce\Locale\Interfaces\CountryInterface;

class Country extends DataObject implements CountryInterface
{
    use CountryTrait;

    public static $db = array(
        'Name' => 'Varchar(255)',
        'CountryCode' => 'Varchar(255)',
        'IsDefault' => 'Boolean'
    );

    public static $summary_fields = array(
        'Name',
        'CountryCode'
    );
}

```
You can see that we've implemented the CountryInterface using the CountryTrait, which is all that you'll need to do in most implementations. An exception to this general rule is if you are using the Shipping Module with Country based shipping. There is another interface that you must use that defines the public function for the shipping cost to that country.

After doing /dev/build and adding a few countries in the database you can use the stored data objects to populate your Country service. All you need to do is to configure the services.yml file in the config folder on your mysite directory. (`/mysite/config/services.yml`)

Example:

```
ecommerce:
  locale_db:
    from: Country
```

This snippet of yml tells the dependency injector to retrieve 'Country' dataobjects from the database and add each instance into the list of locales available to our Locale Service.

You will need to regenerate the container in order to actuate the additions.

Command to regenerate the container: `heystack/bin/heystack generate-container`


## Locale Population with Yaml

Another way to populate the Locale service with Country objects is by directly defining the locales you want in the yaml configuration for your installation. (`/mysite/config/services.yml`)

This is probably best if you want only a limited number of countries that you don't want the CMS user to be able to alter and add to.

Example:

```
ecommerce:
  locale:
    - code: NZ
      name: New Zealand
      default: true
    - code: AU
      name: Australia
      default: false
```

This will add two locales New Zealand, and Australia once the container is regenerated.

Command to regenerate the container: `heystack\bin\heystack generate-container`

The Country class used for the Country objects as populated this way is at `Heystack\Ecommerce\Locale\Country`