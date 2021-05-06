<?php
namespace Core;

use Configuration\Config;

/**
 * Class autoloader
 */
class Autoloader
{
    /**
     * Initialise class autoloading
     * 
     * @return void
     */
    public static function init() {
        spl_autoload_register(function ($class) {
            $class = ltrim($class, '\\');

            $classPath = Config::getBasePath()
                         . __DS__
                         . str_replace('\\', __DS__, $class)
                         . ".php";            

            if(is_file($classPath)) {
                require $classPath;
            }
        });
    }
}
