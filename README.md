# madesimple/php-arrays
Helper functions for manipulating arrays.

## Arr &amp; ArrDots
`Arr` and `ArrDots` contains a set of static helper methods for arrays. These
methods can be used on both `array` and `\ArrayAccess` objects. See the PHPDocs
inside the classes for more information.

## DotArr
`DotArr` is an implementation of `\ArrayAccess` that uses the functions
from `\MadeSimple\ArrDots`. Instances of `DotArr` can be passed into `ArrDots`
as if it were an `array`.

There are three ways to create a `DotArr`:
```php
// Create an empty dot array
$dot = new \MadeSimple\DotArr();

// Create a dot array with a pre-existing array
$dot = new \MadeSimple\DotArr($array);

// Create a dot array with 
$dot = new \MadeSimple\DotArr([
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

Once a `DotArr` is created you can replace the underlining array
in the following ways:
```php
// Set an array after dot array
// Changes will _not_ be reflected in the original array
$dot->setArray($array);

// Set an array as a reference
// Changes will be reflected in the original array
$dot->setReference($array);
```

Basic usage of `DotArr`:
```php
// Get a value using dot notation:
echo "Post Code: ", $dot['address.postCode'], PHP_EOL;

// Set a value using dot notation:
$dot['address.postCode'] = 'EF45 6GH';
echo "Post Code: ", $dot['address.postCode'], PHP_EOL;

// Remove a value using dot notation:
unset($dot['address.postCode']);
echo "Exists: ", (isset($dot['address.postCode']) ? 'yes' : 'no'), PHP_EOL;

// Add a value using dot notation:
$dot['address.postCode'] = 'IJ78 9KL';
echo "Post Code: ", $dot['address.postCode'], PHP_EOL;

// Access nth element in an sub array
echo "Comment: ", $dot['comments.1'], PHP_EOL;
```
