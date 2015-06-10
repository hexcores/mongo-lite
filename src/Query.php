<?php namespace Hexcores\MongoLite;

use MongoCollection;
use MongoDate;
use MongoId;

class Query
{
	protected static $date = true;

	protected $collection;

	protected $wheres = [];

	protected $sorts;

	protected $limit;

	protected $skip;

	/**
	 * Set enable or disable auto fill
	 * "created_at" and "updated_at" date field
	 *
	 * @param  boolean $bool
	 * @return void
	 */
	public static function date($bool)
	{
		static::$date = (boolean) $bool;
	}

	public function __construct(MongoCollection $collection)
	{

		$this->collection = $collection;
	}

	public function count(array $criteria = [])
	{
		return $this->collection->count($criteria);
	}

	public function first($criteria)
	{
		$criteria = $this->prepareCriteria($criteria);

		return $this->collection->findOne($criteria);
	}

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

	public function where($key, $operator = null, $value = null)
	{
		if ( is_array($key))
		{
			$this->wheres = $key;
		}

		return $this;
	}

	public function sort($sorts = [])
	{
		$this->sorts = array_merge($this->sorts, $sorts);

		return $this;
	}

	public function limit($limit)
	{
		$this->limit = $limit;

		return $this;
	}

	public function skip($skip)
	{
		$this->limit = $skip;

		return $this;
	}

	public function get($fields = [])
	{
		$cursor = $this->collection->find($this->wheres, $fields);

		if ( ! empty($sorts))
		{
			$cursor->sort($sorts);
		}

		if ( $limit)
		{
			$cursor->limit($limit);
		}

		if ( $skip)
		{
			$cursor->skip($skip);
		}

		return $cursor;
	}

	public function insert(array $data)
	{
		$data['created_at'] = $this->getFreshDate();
		$data['updated_at'] = $this->getFreshDate();

		$res = $this->collection->insert($data, ['w' => 1]);

		if ( $res['ok'] == 1)
		{
			return $data;
		}

		return false;
	}

	public function update($criteria, array $data)
	{
		$data['updated_at'] = $this->getFreshDate();

		$data = ['$set' => $data];

		$criteria = $this->prepareCriteria($criteria);

		$res = $this->collection->update($criteria, $data, ['w' => 1]);

		if ( $res['ok'] == 1)
		{
			return $data;
		}

		return false;
	}

	public function delete($criteria)
	{
		$criteria = $this->prepareCriteria($criteria);
	}

	public function index($indexs)
	{
		$this->collection->ensureIndex($indexs);
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