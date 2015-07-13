<?php namespace Hexcores\MongoLite;

use MongoClient;
use RuntimeException;

/**
 * Mongo Connection class.
 *
 * @package Hexcores\MongoLite
 * @author  Nyan Lynn Htut <lynnhtut87@gmail.com>
 * @link https://github.com/hexcores/mongo-lite
 */

class Connection
{	
	/**
	 * Instance lists of your connection.
	 *
	 * @var array
	 */
	protected static $instances;

	/**
	 * MongoClient instance for current connection.
	 *
	 * @var \MongoClient
	 */
	protected $client;

	/**
	 * MongoDB instance of the current connected database.
	 *
	 * @var \MongoDB
	 */
	protected $db;

	/**
	 * Create mongodb database connection.
	 *
	 * @param  string $host Host name of mongo database
	 * @param  string|int $port Mongodb port number
	 * @param  string $database Database name for connection
	 * @param  mixed $user Mongodb auth user. This is optional
	 * @param  mixed $password Mongodb password for auth user. This is optional
	 * @param  string $name Connecton name. This is optional
	 * @return void
	 */
	public static function connect($host, $port, $database, $user = null, $password = null, $name = 'default')
	{
		$url = static::prepareServerUrl($host, $port, $user, $password);

		static::$instances[$name] = new static($url, $database);
	}

	/**
	 * Get mongo conenction instance with connected name.
	 *
	 * @param  string $name Connection name. This is optional
	 * @return \Hexcores\MongoLite\Connection
	 * @throws \RuntimeException If your connection name is not already connected.
	 */
	public static function instance($name = 'default')
	{
		if ( ! isset(static::$instances[$name]) )
		{
			throw new RuntimeException("Need to connect for your connection '$name'");
		}

		return static::$instances[$name];
	}

	/**
	 * Prepare mongodb server url.
	 *
	 * @param  string $host
	 * @param  string $port
	 * @param  string $user
	 * @param  string $password
	 * @return string
	 */
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

	/**
	 * Create new connection instance.
	 *
	 * @param string $url Mongo database url
	 * @param string $database Database name from mongo db
	 */
	public function __construct($url, $database)
	{
		$this->client = new MongoClient($url);

		$this->db = $this->client->selectDB($database);
	}

	/**
	 * Get MongoClient instance from current connection.
	 *
	 * @return \MongoClient
	 */
	public function getClient()
	{
		return $this->client;
	}

	/**
	 * Get MongoDB instance from current connection.
	 *
	 * @return \MongoDB
	 */
	public function getDB()
	{
		return $this->db;
	}

	/**
	 * Get MongoCollection from database
	 *
	 * @param  string $collectionName
	 * @return \MongoCollection
	 */
	public function collection($collectionName)
	{
		return $this->db->{$collectionName};
	}

	/**
	 * Drop the database
	 *
	 * @return boolean
	 */
	public function drop()
	{
		$ok = $this->db->drop();

		if ( 1 == (int) $ok['ok'])
		{
			return true;
		}

		return false;
	}
}