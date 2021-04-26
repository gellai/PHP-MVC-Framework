<?php
namespace Database;

/**
 * Database engine abstract class
 */
abstract class Engine
{
    /**
     *
     * @var object Database connection instance
     */
    protected static $_db;

    /**
     * 
     * Return the database connection instance
     * 
     * @return object
     */
    public static function getInstance() {
        return static::$_db;
    }
    
    abstract public static function connect();
}