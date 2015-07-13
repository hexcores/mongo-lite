# Mongo Lite PHP

Mongo Lite PHP is mongodb lite package for php.

## Installation

You can install library through Composer:

Add the following to the composer.json file...

```javascript
{
    "require": {
        "hexcores/mongo-lite": "dev-master"
    }
}
```

Or

Install from terminal

```
$ composer require 'hexcores/mongo-lite:dev-master'
```

## Usage

You need to configure Mongodb connection at first step.

```php
$host = 'localhost';
$port = 27017;
$database = 'mongo_lite';

Hexcores\MongoLite\Connection::connect($host, $port, $database);
```

Now we can use Mongo Lite.

```php
$connection = \Hexcores\MongoLite\Connection::instance($name);
$userCollection = new \Hexcores\MongoLite\Query($connection->collection('users'));
// or simply
$userCollection  = mongo_lite('users');
```

### CRUD with MongoLite

#### Create new record

*`"created_at"` and `"updated_at"` fields will be auto filled by `mongo_lite`*

```php
$user = ['name' => 'Nyan', 'email' => 'nyan@example.com'];

$userCollection  = mongo_lite('users');
$userCollection->insert($user);
// or simply
mongo_lite_insert('users', $user);
```

#### Update a record

*`mongo_lite` will be auto update for `updated_at` time*

```php
$updateName = ['name' => 'Lynn'];
$query = ['email' => 'nyan@exmapl.com'];

$userCollection  = mongo_lite('users');
$userCollection->update($query, $updateName);
// or simply
mongo_lite_update('users', $query, $updateName);
```

#### Delete a record

```php
$query = ['email' => 'nyan@exmapl.com'];

$userCollection  = mongo_lite('users');
$userCollection->delete($query);
// or simply
mongo_lite_delete('users', $query);
```

#### Find the record from database


Find all users from collection.

```php
$users = mongo_lite('users')->all();

if ( count($users) > 0)
{
	foreach ( $users as $user)	
	{
		echo $user->name . '<br>';
	}
}

```

Find only one record from database.

```php
// Find user by mongo id string
$user = mongo_lite('users')->first('5579227f8e973cdf148b4567');

// Find user by MongoId instance
$user = mongo_lite('users')->first(new MongoId('5579227f8e973cdf148b4567'));

// Find user by email
$user = mongo_lite('users')->first(['email' => 'nyan@example.com']);

echo $user->name;
```

#### Increment and decrement of MongoDB

```php
// Increment value 1 to page view field where 'slug' is equal 'about'
$page->increment(['slug' => 'about'], 'views');

// Increment value 5 to page view field where 'slug' is equal 'about'
$page->increment(['slug' => 'about'], 'views', 5);

// Decrement value 1 to page view field where 'slug' is equal 'about'
$page->increment(['slug' => 'about'], 'views', -1);

// DEcrement value 5 to page view field where 'slug' is equal 'about'
$page->increment(['slug' => 'about'], 'views', -5);
```

#### Using in Laravel and LumenPHP

MongoLite already have a `ServiceProvider` for Laravel and LumenPHP Framework.

```php
// Add ServiceProvider in 'config\app.php'
Hexcores\MongoLite\Laravel\MongoLiteServiceProvider
```