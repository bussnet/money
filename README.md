Money
=====

PHP 5.3+ library to make working with money safer, easier, and fun!

This is a fork of [Mathias Verraes' Money Library][4], extended with:
* add CurrencyLookup, for different currency sources (example for json-file included)
* extended List of currencies with settings (decimal_mark, subunit_factor, symbols, iso_code etc) from great [RubyMoney][5] (ISO 4217)
* add format method for formating the Money-string


```php
<?php

use Money\Money;

$fiveEur = Money::EUR(500);
$tenEur = $fiveEur->add($fiveEur);

list($part1, $part2, $part3) = $tenEur->allocate(array(1, 1, 1));
assert($part1->equals(Money::EUR(334)));
assert($part2->equals(Money::EUR(333)));
assert($part3->equals(Money::EUR(333)));
```

The documentation (before the fork) is available at http://money.readthedocs.org


Installation
------------

Install the library using [composer][1]. Add the following to your `composer.json`:

```json
{
    "require": {
        "bnnet/bnmoney": "~1.0"
    }
}
```

Now run the `install` command.

```sh
$ composer.phar install
```

Integration
-----------

See [`MoneyBundle`][2] for [Symfony integration][3] (only before the fork).

[1]: http://getcomposer.org/
[2]: https://github.com/pink-tie/MoneyBundle/
[3]: http://symfony.com/
[4]: https://github.com/mathiasverraes/money
[5]: https://github.com/RubyMoney/money