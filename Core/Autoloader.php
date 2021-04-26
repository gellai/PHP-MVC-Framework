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
                         . DIRECTORY_SEPARATOR
                         . str_replace('\\', DIRECTORY_SEPARATOR, $class)
                         . ".php";            

            if(is_file($classPath)) {
                require $classPath;
            }
        });
    }
}
