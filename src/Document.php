<?php namespace Hexcores\MongoLite;

use ArrayAccess;

/**
 * Document class for mongo data record.
 *
 * @package Hexcores\MongoLite
 * @author  Nyan Lynn Htut <lynnhtut87@gmail.com>
 * @link https://github.com/hexcores/mongo-lite
 */

class Document implements ArrayAccess
{
	protected $query;

	protected $attributes;

	/**
	 * Create new document instance.
	 *
	 * @param \Hexcores\MongoLite\Query $query
	 * @param array $attributes
	 */
	public function __construct(Query $query, $attributes = [])
	{
		$this->query = $query;

		$this->attributes = $attributes;
	}

	/**
	 * Convert the document instance to JSON.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
	}

	/**
	 * Convert the document instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		$attrs = $this->attributes;

		if ( isset($attrs['_id']))
		{
			$attrs['id'] = (string) $attrs['_id'];
			unset($attrs['_id']);
		}

		return $attrs;
	}

	/**
	 * Convert the document instance to string representation.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->toJson();
	}

	/**
     * Dynamically retrieve attributes from document.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
    	if ( isset($this->attributes[$key]))
    	{
    		return $this->attributes[$key];
    	}

        return null;
    }

    /**
     * Dynamically set attribute to the document.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
	 * Determine if an attribute exists on the document.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->attributes[$key]);
	}

	/**
	 * Unset an attribute on the document.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function __unset($key)
	{
		unset($this->attributes[$key]);
	}

    /**
	 * Determine if the given attribute exists in document.
	 *
	 * @param  mixed  $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->{$offset});
	}

	/**
	 * Get the value for a given offset from document.
	 *
	 * @param  mixed  $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->$offset;
	}

	/**
	 * Set the value for a given offset to document.
	 *
	 * @param  mixed  $offset
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->$offset = $value;
	}

	/**
	 * Unset the value for a given offset from document.
	 *
	 * @param  mixed  $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->$offset);
	}
}
