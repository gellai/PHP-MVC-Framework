<?php
namespace App\Models;

/**
 * Dummy database
 * 
 * Database connection and methods can be created in this class
 */
class DummyDatabase extends \Core\Model
{
    private $_dbResult;
    
    /**
     * Class constructor
     * 
     * @return void
     */
    public function __construct() {
        $this->_dbResult = "Data retrieved from dummy database.";
    }
    
    /**
     *  
     * @return string
     */
    public function getDBData() {
        return $this->_dbResult;
    }
}
