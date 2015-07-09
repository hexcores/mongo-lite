<?php

require __DIR__ . '/../vendor/autoload.php';

// Configure Mongo Lite Connection
Hexcores\MongoLite\Connection::connect('localhost', 27017, 'mongo_lite');

$users = mongo_lite('users')->delete(['name' => 'Nyan Lynn Htut']);
var_dump($users); exit;
foreach ($users as $user) {
	echo $user->name . '</br>';
	echo $user->job . '</br>';
	echo $user->toJson() . '<br>';
	echo '<hr>';
}