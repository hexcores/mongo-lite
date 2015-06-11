<?php

if ( ! function_exists('mongo_lite'))
{

	function mongo_lite($collection, $name = 'default')
	{
		$connection = \Hexcores\MongoLite\Connection::instance($name);

		return new \Hexcores\MongoLite\Query($connection->collection($collection));
	}
}

if ( ! function_exists('mongo_lite_insert'))
{

	function mongo_lite_insert($collection, array $data)
	{
		$collection = mongo_lite($collection);

		return $collection->insert($data);
	}
}

if ( ! function_exists('mongo_lite_update'))
{

	function mongo_lite_update($collection, $criteria, array $data)
	{
		$collection = mongo_lite($collection);

		return $collection->update($criteria, $data);
	}
}

if ( ! function_exists('mongo_lite_first'))
{

	function mongo_lite_first($collection, $criteria)
	{
		$collection = mongo_lite($collection);

		return $collection->first($criteria);
	}
}

if ( ! function_exists('mongo_lite_index'))
{

	function mongo_lite_index($collection, $criteria)
	{
		$collection = mongo_lite($collection);

		return $collection->first($criteria);
	}
}