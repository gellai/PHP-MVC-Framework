<?php
namespace Database;

use \PDO;
use \PDOException;
use Database\Exceptions\ConnectionException;
use Configuration\Config;

/**
 * Database connection class
 */
class DBConnection
{
    /**
     *
     * @var object Database engine instance
     */
    private $_DBEngine;
    
    /**
     *
     * @var object PDO statement
     */
    private $_stmt;
    
    /**
     * Constructor method to load database engine
     * 
     * @throws ConnectionException
     * @return void
     */
    public function __construct() {
        $engine = Config::getConfigValue('db', 'engine');
        $engineClass = 'Database\Engines\\' . $engine;

        if(class_exists($engineClass)) {
            $engineClass::connect();
            $this->_DBEngine = $engineClass::getInstance();
        }
        else {
            throw new ConnectionException("Engine '$engine' not found! Check if class exists in Database\Engines. Engine name in the configuration file is case sensitive.", "error");
        }
    }
    
    /**
     * Prepare the SQL database request string
     * 
     * @param string $query SQL database query string
     * @return $this
     */
    public function query($query) {
        $this->_stmt = $this->_DBEngine->prepare($query);
        return $this;
    }
    
    /**
     * Execute the query string
     * 
     * @throws ConnectionException
     * @return $this
     */
    public function execute() {
        try {
            $this->_stmt->execute();
        }
        catch(PDOException $e) {
            throw new ConnectionException($e->getMessage(), "warning");
        }
        
        return $this;
    }
    
    /**
     * Return an array of the query result
     * 
     * @return array
     */
    public function fetchAll() {
        return $this->_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Return an array of the first element of the query result
     * 
     * @return array
     */
    public function fetch() {
        return $this->_stmt->fetch(PDO::FETCH_ASSOC);
    }
}