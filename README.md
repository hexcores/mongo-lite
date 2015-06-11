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

```php
$user = ['name' => 'Nyan', 'email' => 'nyan@example.com'];

$userCollection  = mongo_lite('users');
$userCollection->insert($user);
// or simply
mongo_lite_insert('users', $user);
```

#### Update a record

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