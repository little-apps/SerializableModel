# SerializableModel

[![Build Status](https://travis-ci.org/little-apps/SerializableModel.svg?branch=master)](https://travis-ci.org/little-apps/SerializableModel) [![Coverage Status](https://coveralls.io/repos/github/little-apps/SerializableModel/badge.svg?branch=master)](https://coveralls.io/github/little-apps/SerializableModel?branch=master) [![Latest Stable Version](https://poser.pugx.org/little-apps/serializable-model/v/stable)](https://packagist.org/packages/little-apps/serializable-model) [![Total Downloads](https://poser.pugx.org/little-apps/serializable-model/downloads)](https://packagist.org/packages/little-apps/serializable-model) [![Latest Unstable Version](https://poser.pugx.org/little-apps/serializable-model/v/unstable)](https://packagist.org/packages/little-apps/serializable-model) [![License](https://poser.pugx.org/little-apps/serializable-model/license)](https://packagist.org/packages/little-apps/serializable-model)

SerializableModel is simple package for serializable columns in a Laravel model. It utilizes the [``serialize``](http://php.net/manual/en/function.serialize.php) and [``unserialize``](http://php.net/manual/en/function.unserialize.php) PHP functions to store values in the database.

## License
SerializableModel is free and open source, and is licensed under the MIT License.

    Copyright 2018 Little Apps
    
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    
    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    
    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
    
## Installation

Install using composer:

```bash
cd /path/to/laravel/app
composer require little-apps/serializable-model
```

## Usage

This package includes a [trait](http://php.net/trait) which can be included in any class that inherits ``Illuminate\Database\Eloquent\Model``.

```php
use LittleApps\SerializableModel\Serializable;
use Illuminate\Database\Eloquent\Model;

class Foo extends Model {
   use Serializable;
}
```

The next step is to define the columns that should be serialized in the ``$serializable`` property.

```php
use LittleApps\SerializableModel\Serializable;
use Illuminate\Database\Eloquent\Model;

class Foo extends Model {
   use Serializable;
   
   protected $serializable = [
       'column1',
       'column2',
       'column3',
       'column4',
   ];
}
```

Any value that is assigned to the ``addresses`` or ``phone_numbers`` columns will stored in the database as a string representation of the original data type.

```php
$foo = new Foo();

// Will be stored in the database as "i:9999;"
$foo->column1 = 9999;

// Will be stored in the database as "a:1:{s:3:"key";s:5:"value";}"
$foo->column2 = ['key' => 'value'];

// Will be stored in the database as "d:96.67;"
$foo->column3 = 96.67;

// Will be stored in the database as "Hello World"
$foo->column4 = 'Hello World';
```

The value of columns are unserialized (if nesessary) and returned. The following follows the values set in the example above.

```php
// $value will be set to 9999
$value = $foo->column1;

// $value will be set to ['key' => 'value']
$value = $foo->column2;

// $value will be set to 96.67
$value = $foo->column3;

// $value will be set to "Hello World"
$value = $foo->column4;
```

## Database Migrations

The data type to use for a serializable column in a MySQL database can vary. The PHP documentation for [``serializable``](http://php.net/manual/en/function.serialize.php) and [this answer on StackOverflow](https://stackoverflow.com/a/10771079/533242) recommends it be a ``BLOB``, and not ``CHAR`` or ``TEXT``. The command for a ``BLOB`` in a Laravel database migration is ``binary()``.

```php
Schema::table('foo', function (Blueprint $table) {
    $table->binary('column1');
});
```

## Notes

 * To save space in the database, strings are left as is and not serialized.
 * [Resources](http://php.net/manual/en/language.types.resource.php) can't be serialized and trying to do so will result in undefined behavior.

## Show Your Support ##

Little Apps relies on people like you to keep our software running. If you would like to show your support for Little System Cleaner, then you can [make a donation](https://www.little-apps.com/?donate) using PayPal, Payza or credit card (via Stripe). Please note that any amount helps (even just $1).
