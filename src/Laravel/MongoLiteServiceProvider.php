<?php namespace Hexcores\MongoLite\Laravel;

use Illuminate\Support\ServiceProvider;
use Hexcores\MongoLite\Connection;
use Hexcores\MongoLite\Query;

class MongoLiteServiceProvider extends ServiceProvider
{
	/**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    	$config = $this->app['config']->get('mongo_lite');

        Connection::connect(
        	$config['host'], 
        	$config['port'], 
        	$config['database'], 
        	$config['user'], 
        	$config['password']
        );
    }

	/**
     * Register the service provider.
     *
     * @return void
     */
	public function register() {}
}