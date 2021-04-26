<?php
namespace Database\Engines;

use \PDO;
use \PDOException;
use Database\Engine;
use Database\Exceptions\EngineException;
use Configuration\Config;

/**
 * SQLite database engine class
 */
class SQLite extends Engine
{
    /**
     * Establish SQLite database connection
     * 
     * @throws EngineException
     * @return void
     */    
    public static function connect() {
        if(static::$_db === null) {
            // SQLite database file located in Database/ folder
            $sqliteFilePath = Config::getBasePath() . Config::getConfigValue("db", "sqlite_file");
            
            try {
                $dsn = "sqlite:" . $sqliteFilePath;
                static::$_db = new PDO($dsn);
                static::$_db->setAttribute(
                                PDO::ATTR_ERRMODE,
                                PDO::ERRMODE_EXCEPTION
                              );
            }
            catch(PDOException $e) {
                throw new EngineException($e->getMessage(), "error");
            }
        }
    }
}