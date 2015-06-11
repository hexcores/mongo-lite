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

	public function __construct(MongoCollection $collection)
	{

		$this->collection = $collection;
	}

	public function collection()
	{
		return $this->collection;
	}

	public function count(array $criteria = [])
	{
		return $this->collection->count($criteria);
	}

	public function first($criteria = [])
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

		if ( $this->limit)
		{
			$cursor->limit($limit);
		}

		if ( $this->skip)
		{
			$cursor->skip($skip);
		}

		$results = [];

		foreach ($cursor as $k => $v)
		{
			$results[] = $this->toDocument($v);
		}

		return $results;
	}

	public function insert(array $data)
	{
		$data['created_at'] = $this->getFreshDate();
		$data['updated_at'] = $this->getFreshDate();

		$res = $this->collection->insert($data, ['w' => 1]);

		if ( $res['ok'] == 1)
		{
			return $this->toDocument($data);
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