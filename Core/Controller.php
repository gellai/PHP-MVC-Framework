<?php
namespace Core;

use Core\Exceptions\ControllerException;

/**
 * Core controller model class
 */
abstract class Controller
{
    /**
     * 
     * @var object Controller class instances
     */
    private static $_instances = array();
    
    /**
     * 
     * @var array Query parameters holder
     */
    private $_params = array();

    /**
     * Method before the controller action
     * 
     * @return void
     */
    abstract protected function before(): void;
    
    /**
     * Method after the controller action
     * 
     * @return void
     */
    abstract protected function after(): void;     
    
    /**
     * Setting the requested controller class instance,
     * adding the Helper class instance and returning it.
     * The controller class instance is saved 
     * statically to $_instances.
     * 
     * @return object App/Controller class instance
     */
    public static function getClassInstance() {
        $callingClass = get_called_class();
        
        if( !isset(static::$_instances[$callingClass]) ) {
            static::$_instances[$callingClass] = new $callingClass();     
        }
        
        static::$_instances[$callingClass]->helper = new \Core\Helper;
        
        return static::$_instances[$callingClass];
    }
    
    /**
     * Putting the query parameters into the
     * holder array
     * 
     * @param array $params
     * @return void
     */
    public function setParameters($params) {
        $this->_params = $params;
    }
    
    /**
     * Return all query parameters as an array
     * 
     * @return array
     */
    public function getParameters() {
        return $this->_params;
    }


    /**
     * Set a parameter
     * 
     * @param string $key
     * @param string $value
     * @return void
     * @throws ControllerException
     */
    public function setParameter($key, $value) {
        $this->_params[$key] = $value;
    }
    
    /**
     * Return a parameter
     * 
     * @param string $param
     * @return string
     * @throws ControllerException
     */
    public function getParameter($param) {
        if(isset($this->_params[$param])) {
            return $this->_params[$param];
        }
        else {
            throw new ControllerException("Parameter '$param' doesn't exist!", "error");
        }
    }
    
    /**
     * Run the a method (action) of a controller instance
     * 
     * @param string $method
     * @return void
     */
    public function runActionMethod(string $method) {        
        $this->before();       
        
        call_user_func_array(
            array($this, $method), array()
        );
        
        $this->after();
    }    
}

