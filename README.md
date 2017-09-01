# madesimple/php-arrays
[![Build Status](https://travis-ci.org/pdscopes/php-arrays.svg?branch=master)](https://travis-ci.org/pdscopes/php-arrays)

Helper functions for manipulating arrays.

## Arr &amp; ArrDots
`Arr` and `ArrDots` contains a set of static helper methods for arrays. These
methods can be used on both `array` and `\ArrayAccess` objects. See the PHPDocs
inside the classes for more information.

## Dots
`Dots` is an implementation of `\ArrayAccess` that uses the functions
from `\MadeSimple\ArrDots`. Instances of `Dots` can be passed into `ArrDots`
as if it were an `array`.

There are three ways to create a `Dots`:
```php
// Create an empty dot array
$dots = new \MadeSimple\Dots();

// Create a dot array with a pre-existing array
$dots = new \MadeSimple\Dots($array);

// Create a dot array with 
$dots = new \MadeSimple\Dots([
    'address' => [
        'houseNo'  => '123',
        'street'   => 'Fake St',
        'postCode' => 'AB12 3CD',
    ],
    'comments' => [
        'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        'Donec nec pellentesque est.',
        'Quisque volutpat quam et est laoreet, vitae consectetur erat molestie.',
    ]
]);
```

Once a `Dots` is created you can replace the underlining array
in the following ways:
```php
// Set an array after dot array
// Changes will _not_ be reflected in the original array
$dots->setArray($array);

// Set an array as a reference
// Changes will be reflected in the original array
$dots->setReference($array);
```

Basic usage of `Dots`:
```php
// Get a value using dot notation:
echo "Post Code: ", $dots['address.postCode'], PHP_EOL;

// Set a value using dot notation:
$dots['address.postCode'] = 'EF45 6GH';
echo "Post Code: ", $dots['address.postCode'], PHP_EOL;

// Remove a value using dot notation:
unset($dots['address.postCode']);
echo "Exists: ", (isset($dots['address.postCode']) ? 'yes' : 'no'), PHP_EOL;

// Add a value using dot notation:
$dots['address.postCode'] = 'IJ78 9KL';
echo "Post Code: ", $dots['address.postCode'], PHP_EOL;

// Access nth element in an sub array
echo "Comment: ", $dots['comments.1'], PHP_EOL;
```
