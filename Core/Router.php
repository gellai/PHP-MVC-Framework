<?php
namespace Core;

use Core\Exceptions\RouterException;

/**
 * Routing class
 */
class Router extends \Configuration\Config
{
    /**
     * Holds the URL query string.
     * 
     * Example:
     *  
     *  www.example.com/database/post?id=1&page=2&key=abcd
     *  
     *  array() {
     *      ['controller'] = 'database',
     *      ['action'] = 'post',
     *      ['param'] = array() {
     *              ['id'] = 1,
     *              ['page'] = 2,
     *              ['key'] = 'abcd'
     *      }
     *  }
     * 
     * @var array Controller/action query string holder
     */
    private $_routes = array();   
    
    /**
     * Set default controller/action to index page.
     * 
     * URL: example.com/
     * 
     * @return void
     */    
    public function __construct() {
        // Set default controller/action to start page
        $this->_routes['controller'] = "Home";
        $this->_routes['action'] = "index";
    }

    /**
     * Dispatcher to process controller and action
     * parameters from the URL route address.
     * 
     * @param string $urlQuery
     * @return void
     */    
    public function dispatch($urlQuery) {
        $route = $this->_removeQueryParameters($urlQuery);
        $this->_setRoute($route);
        
        $params = $this->_getQueryParametersData($urlQuery);
        $controllerClass = 'App' . __DS__ . 'Controllers' . __DS__ . $this->_routes['controller'] . "Controller";   
        
        // Check if controller class exists
        if(class_exists($controllerClass)) {
            $controllerObj = $controllerClass::getClassInstance();
            $controllerObj->setParameters($params);
            $actionMethod = $this->_routes['action'] . 'Action';
            
            // Verify if action method exists in the controller
            if(method_exists($controllerObj, $actionMethod)) {
                $controllerObj->runActionMethod($actionMethod);
            }
            else {
                $m = $this->_routes['action'];
                throw new RouterException("Method $m doesn't exist.", "warning");
            }
        }
        else {
            $c = $this->_routes['controller'];
            throw new RouterException("Controller class $c doesn't exist.", "warning");
        }
    }
    
    /**
     * Process the URL request and set the
     * controller path and action method.
     * 
     * @param string $route
     * @return void
     */
    private function _setRoute($route) {
        $route = $this->_rewriteURL($route);

        // Add index action to URL if ends with '/'
        if(substr($route, -1) == "/") {
            $route .= "index";
        }
        
        $routeSections = explode("/", $route);
        $nSections = count($routeSections);
        
        // Check if last array element is empty
        if(empty($routeSections[$nSections-1])) {
            unset($routeSections[$nSections-1]);
            $nSections = count($routeSections);
        }

        // www.example.com/database
        if($nSections == 1) { 
            $this->_routes['controller'] = $this->_convertToStudlyCaps($routeSections[0]);
        }
        // www.example.com/database/post
        elseif($nSections == 2) { 
            $this->_routes['controller'] = $this->_convertToStudlyCaps($routeSections[0]);
            $this->_routes['action'] = $this->_convertToCamelCase($routeSections[1]);
        }
        // www.example.com/database/list/post
        elseif($nSections > 2) {
            $this->_routes['action'] = $this->_convertToCamelCase($routeSections[$nSections-1]);
            unset($routeSections[$nSections-1]);
            
            $tmpControllerRoute = "";            
            
            for($i=0; $i < $nSections-1; $i++) {
                $tmpControllerRoute .= $routeSections[$i] . "/";
            }
            
            $controllerRoute = rtrim($tmpControllerRoute, "/");
            $this->_routes['controller'] = $this->_convertToStudlyCaps($controllerRoute);             
        }
    }
    
    /**
     * Translate the URL slug to the linked controller
     * if it's set in Application.config
     * 
     * @param string $slug
     * @return string
     */
    private function _rewriteURL($slug) {
        return isset(static::$_configuration['url'][$slug]) ? static::$_configuration['url'][$slug] : $slug;
    }
    
    /**
     * Remove the query parameters from URL
     * database/post?id=1&page=2&key=abcd   => database/post
     * ?id=1&page=2&key=abcd                => returns empty string 
     * 
     * @param string $urlQuery
     * @return string
     */
    protected function _removeQueryParameters($urlQuery) {
        if( !empty($urlQuery) ) {
            $sections = explode('&amp;', $urlQuery, 2);
            
            if(strpos($sections[0], '=') === false) {
                $urlQuery = $sections[0];
            }
            else {
                $urlQuery = '';            
            }
        }
        
        return $urlQuery;
    }
    
    /**
     * Extract query route from URL
     * ?id=1&page=2&key=abcd
     *  array() {
     *      [id] = 1,
     *      [page] = 2,
     *      [key] = "abcd"
     *  }
     * 
     * @param string $urlQuery
     * @return array
     */
    protected function _getQueryParametersData($urlQuery) {
        $qData = array();
        
        $sections = explode('&amp;', $urlQuery);
        
        if(strpos($sections[0], '=') === false) {
            unset($sections[0]);
        }
        
        foreach($sections as $section) {
            $tmp = explode('=', $section);
            $qData[$tmp[0]] = filter_input(INPUT_GET, $tmp[0], FILTER_SANITIZE_STRING);
        }
        
        return $qData;
    }
    
    /**
     * Convert string to StudlyCaps
     * some-string => SomeString
     * some/string => Some/String
     * 
     * @param string $str
     * @return string
     */
    protected function _convertToStudlyCaps($str) {
        $tRoute = preg_replace('([\\\/])', __DS__, $str);
        $tRoute = str_replace(' ', __DS__,
                            ucwords(str_replace(__DS__, ' ', strtolower($tRoute)))
                        );
        
        return str_replace(' ', '', 
                    ucwords(str_replace('-', ' ', $tRoute))
                );
    }
    
    /**
     * Convert string to CamelCase
     * some-string => someString
     * 
     * @param string $str
     * @return string
     */
    protected function _convertToCamelCase($str) {
        return lcfirst(
            $this->_convertToStudlyCaps($str)
        );
    }    
}