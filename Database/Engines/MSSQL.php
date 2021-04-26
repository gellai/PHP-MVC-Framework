<?php
namespace Database\Engines;

use \PDO;
use \PDOException;
use Database\Engine;
use Database\Exceptions\EngineException;
use Configuration\Config;

/**
 * Microsoft SQL Server database engine class
 */
class MSSQL extends Engine
{
    /**
     * Establish Microsoft SQL Server database connection
     * 
     * @throws EngineException
     * @return void
     */
    public static function connect() {    
        if(static::$_db === null) {
            $_options = array(
                // Presistent connection
                PDO::ATTR_PERSISTENT => true, 
                // Throw an exception if an error occurs
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION                
            );
            
            $host = Config::getConfigValue('db', 'host');
            $port = Config::getConfigValue('db', 'port');
            $user = Config::getConfigValue('db', 'user');
            $pass = Config::getConfigValue('db', 'password');
            $database = Config::getConfigValue('db', 'database');

            try {
                $dsn = "dblib:host=$host;port=$port;dbname=$database";
                static::$_db = new PDO($dsn, $user, $pass, $_options);
            } 
            catch(PDOException $e) {
                throw new EngineException($e->getMessage(), "error");
            }
        }    
    }   
}