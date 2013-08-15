# Ecommerce-core
The e-commerce core module is a collection of services and interfaces that are essential to a functional e-commerce system. It sits on top the system implemented in the Heystack.

It is recommended that both the Locale and Currency services are used throughout the development of the application utilising the e-commerce system. This ensures consistency and will make the application more maintainable.

Heystack is used for the handling the state of the application as well as the storage of data. There are several other utilities and systems that are implemented in the Heystack that this module depends on.

## Requirements
* Heystack

## Services
1. [Currency Service](./currency.md) - Stores and defines currency information
2. [Locale Service](./locale.md) - Stores and defines locale information
3. [Transaction Service](./transaction.md) - Stores and defines transaction information

## Unimplemented Interfaces

These interfaces are used in the e-commerce core module but is left to be implemented by either the developer or in other modules.

1. Purchasable Holder - Interface that defines methods necessary to implement the 'shopping cart'
2. Purchasable - Interface that defines methods necessary to implement a  'product'



