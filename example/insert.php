<?php

require __DIR__ . '/../vendor/autoload.php';

// Configure Mongo Lite Connection
Hexcores\MongoLite\Connection::connect('localhost', 27017, 'mongo_lite');

$nyan = [
	'name'	=> getName(),
	'job'	=> getJob()
];

$user = mongo_lite_insert('users', $nyan);

var_dump($user);


function getName()
{
	$names = ['Yan', 'Nyan', 'Thet', 'Li', 'Jia', 'Naing', 'Lynn', 'Paing', 'Htut', 'Oo'];
	
	return $names[array_rand($names)];
}

function getJob()
{
	$jobs = ['Developer', 'Designer', 'Manager'];

	return $jobs[array_rand($jobs)];
}