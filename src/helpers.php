<?php

if ( ! function_exists('mongo_lite'))
{
	/**
	 * Helper for mongo lite query instance.
	 *
	 * @param  string $collection
	 * @param  string $name
	 * @return \Hexcores\MongoLite\Query
	 */
	function mongo_lite($collection, $name = 'default')
	{
		$connection = \Hexcores\MongoLite\Connection::instance($name);

		return new \Hexcores\MongoLite\Query($connection->collection($collection));
	}
}

if ( ! function_exists('mongo_lite_insert'))
{
	/**
	 * Create a new record to the database with mongo lite.
	 *
	 * @param  string $collection
	 * @param  array $data
	 * @return \Hexcores\MongoLite\Document|bool
	 */
	function mongo_lite_insert($collection, array $data)
	{
		$collection = mongo_lite($collection);

		return $collection->insert($data);
	}
}

if ( ! function_exists('mongo_lite_update'))
{
	/**
	 * Update a record in the database with mongo lite.
	 *
	 * @param  string $collection
	 * @param  array $criteria
	 * @param  array $data
	 * @return bool
	 */
	function mongo_lite_update($collection, $criteria, array $data)
	{
		$collection = mongo_lite($collection);

		return $collection->update($criteria, $data);
	}
}

if ( ! function_exists('mongo_lite_delete'))
{
	/**
	 * Delete a record in the database with mongo lite.
	 *
	 * @param  string $collection
	 * @param  array $criteria
	 * @return bool
	 */
	function mongo_lite_delete($collection, $criteria)
	{
		$collection = mongo_lite($collection);

		return $collection->delete($criteria);
	}
}

if ( ! function_exists('mongo_lite_first'))
{
	/**
	 * Get the first result match with given criteria.
	 *
	 * @param  string $collection
	 * @param  array $criteria
	 * @return \Hexcores\MongoLite\Document|null
	 */
	function mongo_lite_first($collection, $criteria)
	{
		$collection = mongo_lite($collection);

		return $collection->first($criteria);
	}
}

if ( ! function_exists('mongo_lite_index'))
{
	/**
	 * Create ensure index for collection.
	 *
	 * @param  string $collection
	 * @param  array $indexs
	 * @return [type]
	 */
	function mongo_lite_index($collection, $indexs)
	{
		$collection = mongo_lite($collection);

		return $collection->index($indexs);
	}
}