<?php namespace Hexcores\MongoLite;

use MongoClient;
use RuntimeException;

class Connection
{
	protected static $instances;

	protected $client;

	protected $db;

	public static function connect($host, $port, $database, $user = null, $password = null, $name = 'default')
	{
		$url = static::prepareServerUrl($host, $port, $user, $password);

		static::$instances[$name] = new static($url, $database);
	}

	public static function instance($name = 'default')
	{
		if ( ! isset(static::$instances[$name]) )
		{
			throw new RuntimeException("Need to connect for your connection '$name'");
		}

		return static::$instances[$name];
	}

	protected static function prepareServerUrl($host, $port, $user, $password)
	{
		$o = 'mongodb://';

		if ( $user && $password)
		{
			$o .= $user . ':' . $password . '@';
		}

		$o .= $host . ':' . $port;

		return $o;
	}

	public function __construct($url, $database)
	{
		$this->client = new \MongoClient($url);

		$this->db = $this->client->selectDB($database);
	}

	public function getClient()
	{
		return $this->client;
	}

	public function getDB()
	{
		return $this->db;
	}

	public function collection($collectionName)
	{
		return $this->db->{$collectionName};
	}
}