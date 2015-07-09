<?php namespace Hexcores\MongoLite;

use MongoId;
use MongoDate;
use MongoCollection;

/**
 * Mongo Query class.
 *
 * @package Hexcores\MongoLite
 * @author  Nyan Lynn Htut <lynnhtut87@gmail.com>
 * @link https://github.com/hexcores/mongo-lite
 */

class Query
{
	protected static $date = true;

	protected $collection;

	protected $wheres = [];

	protected $sorts;

	protected $limit;

	protected $skip;

	/**
	 * Set enable or disable auto fillable
	 * "created_at" and "updated_at" date field
	 *
	 * @param  boolean $bool
	 * @return void
	 */
	public static function date($bool)
	{
		static::$date = (boolean) $bool;
	}

	/**
	 * Create new Query instance.
	 *
	 * @param \MongoCollection $collection
	 */
	public function __construct(MongoCollection $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * Get collection instance from query.
	 * 
	 * @return \MongoCollection
	 */
	public function collection()
	{
		return $this->collection;
	}

	/**
	 * Counts the number of documents in this collection.
	 *
	 * @param  array  $criteria Criteria query for count. This is optional.
	 * @return int
	 */
	public function count(array $criteria = [])
	{
		return $this->collection->count($criteria);
	}

	/**
	 * Get the first result match with given criteria.
	 *
	 * @param  mixed $criteria
	 * @return \Hexcores\MongoLite\Document|null
	 */
	public function first($criteria = [])
	{
		$criteria = $this->prepareCriteria($criteria);

		$result = $this->collection->findOne($criteria);

		return ! is_null($result) ? $this->toDocument($result) : null;
	}

	/**
	 * Get all records from the database.
	 *
	 * @param  int|null $limit
	 * @param  int|null $skip
	 * @return array
	 */
	public function all($limit = null, $skip = null)
	{
		if ( $limit)
		{
			$this->limit($limit);
		}

		if ( $skip)
		{
			$this->skip($skip);
		}

		return $this->get();
	}

	/**
	 * [where description]
	 *
	 * @param  [type] $key      [description]
	 * @param  [type] $operator [description]
	 * @param  [type] $value    [description]
	 * @return [type]
	 */
	public function where($key, $operator = null, $value = null)
	{
		if ( is_array($key))
		{
			$this->wheres = $key;
		}

		return $this;
	}

	/**
	 * Set the "sort" value of the mongo query.
	 *
	 * @param  array $sorts
	 * @return \Hexcores\MongoLite\Query
	 */
	public function sort($sorts = [])
	{
		$this->sorts = array_merge($this->sorts, $sorts);

		return $this;
	}

	/**
	 * Set the "limit" value of the mongo query.
	 *
	 * @param  int  $limit
	 * @return \Hexcores\MongoLite\Query
	 */
	public function limit($limit)
	{
		$this->limit = $limit;

		return $this;
	}

	/**
	 * Set the "skip" value of the mongo query.
	 *
	 * @param  int  $skip
	 * @return \Hexcores\MongoLite\Query
	 */
	public function skip($skip)
	{
		$this->limit = $skip;

		return $this;
	}

	/**
	 * Execute the query and preapre for response data with
	 * array lists of Document instance.
	 *
	 * @param  array  $fields
	 * @return array
	 */
	public function get($fields = [])
	{
		$cursor = $this->collection->find($this->wheres, $fields);

		if ( ! empty($sorts))
		{
			$cursor->sort($sorts);
		}

		if ( $this->limit)
		{
			$cursor->limit($this->limit);
		}

		if ( $this->skip)
		{
			$cursor->skip($this->skip);
		}

		$results = [];

		foreach ($cursor as $k => $v)
		{
			$results[] = $this->toDocument($v);
		}

		return $results;
	}

	/**
	 * Insert a new data record into the database.
	 *
	 * @param  array  $data
	 * @return \Hexcores\MongoLite\Document|bool
	 */
	public function insert(array $data)
	{
		$data['created_at'] = $this->getFreshDate();
		$data['updated_at'] = $this->getFreshDate();

		$res = $this->collection->insert($data, ['w' => 1]);

		if ( 1 == (int) $res['ok'])
		{
			return $this->toDocument($data);
		}

		return false;
	}

	/**
	 * Update a data record in the database with given criteria.
	 *
	 * @param  mixed $criteria
	 * @param  array  $data
	 * @return bool
	 */
	public function update($criteria, array $data)
	{
		$inc = null;

		if ( isset($data['$inc']))
		{
			$inc = $data['$inc'];
			unset($data['$inc']);
		}

		$data['updated_at'] = $this->getFreshDate();

		$data = ['$set' => $data];

		if ( $inc) {
			$data['$inc'] = $inc;
		}

		$criteria = $this->prepareCriteria($criteria);

		$res = $this->collection->update($criteria, $data, ['w' => 1]);

		if ( 1 == (int) $res['ok'])
		{
			return true;
		}

		return false;
	}

	/**
	 * Delete a data record from database with given criteria.
	 *
	 * @param  array $criteria
	 * @return bool
	 */
	public function delete($criteria)
	{
		$criteria = $this->prepareCriteria($criteria);

		$res = $this->collection->remove($criteria);

        if (1 == (int) $res['ok'])
        {
            return true;
        }

        return false;
	}

	/**
	 * Increment a column's value by a given amount.
	 *
	 * @param  array   $criteria
	 * @param  string  $key
	 * @param  integer $value
	 * @return bool
	 */
	public function increment(array $criteria, $key, $value = 1)
	{
		return $this->update($criteria, ['$inc' => [$key => $value]]);
	}

	/**
	 * Create ensure index for collection.
	 *
	 * @param  array $indexs
	 * @return void
	 */
	public function index($indexs)
	{
		$this->collection->ensureIndex($indexs);
	}

	/**
	 * Convert attributes data to Document instance.
	 *
	 * @param  array $attributes
	 * @return \Hexcores\MongoLite\Document
	 */
	protected function toDocument(array $attributes)
	{
		return new Document($this, $attributes);
	}

	/**
	 * Get fresh date for data store.
	 *
	 * @return \MongoDate
	 */
	protected function getFreshDate()
	{
		return new MongoDate();
	}

	/**
	 * Prepare criteria data
	 *
	 * @param  mixed $criteria
	 * @return array
	 */
	protected function prepareCriteria($criteria)
	{
		if ( $criteria instanceof MongoId)
		{
			return ['_id' => $criteria];
		}

		if ( is_string($criteria))
		{
			return ['_id' => new MongoId($criteria)];
		}

		return $criteria;
	}

}