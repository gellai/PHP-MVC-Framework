<?php
namespace Core;

use Core\Exceptions\ModelException;

/**
 * Model class
 */
class Model
{
    /**
     * Array of model objects
     * 
     * @var array 
     */    
    protected static $_instances = array();

    /**
     * Database class instance
     * 
     * @var object
     */
    protected static $_db;
    
    /**
     * Class constructor
     * 
     * @return void
     */
    public function __construct() {
        static::$_db = new \Database\DBConnection;
    }
    
    /**
     * Create and return the instance of 
     * the calling model class
     * 
     * @return object
     */    
    public static function getModelInstance() {
        $callingModel = get_called_class();
        
        // Register the calling model instance
        if(!isset(static::$_instances[$callingModel])) {
            static::$_instances[$callingModel] = new $callingModel();
        }
        
        return static::$_instances[$callingModel];
    }
}