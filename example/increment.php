<?php

require __DIR__ . '/../vendor/autoload.php';

// Configure Mongo Lite Connection
Hexcores\MongoLite\Connection::connect('localhost', 27017, 'mongo_lite');

$nyan = [
	'name'	=> 'John',
	'age'	=> 22
];

mongo_lite_insert('test_increment', $nyan);

mongo_lite('test_increment')->increment(['name' => 'John'], 'age');

$john = mongo_lite('test_increment')->first(['name' => 'John']);

var_dump($john); exit;
