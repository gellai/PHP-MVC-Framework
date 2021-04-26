<?php
namespace Configuration;

use Configuration\Exceptions\ConfigExcetion;

/**
 * Application configuration class
 */
class Config
{
    /**
     *
     * @var string Absolute path of the application
     */
    private static $_basePath;

    /**
     * 
     * @var array Configuration file loaded from Application.config
     */    
    protected static $_configuration = array();
    
    /**
     * Set the absolute path
     * 
     * @param string $base
     * @return void
     */    
    public static function setBasePath($base) {
        static::$_basePath = $base;
    }

    /**
     * Return the absolute path
     * 
     * @return string
     */    
    public static function getBasePath() {
        return static::$_basePath;
    }
    
    /**
     * Return the base URL of the application
     * 
     * @return string 
     */
    public static function getURL() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "" ? "https://" : "http://";
        $rootURL = str_replace("/index.php", "", $_SERVER['SCRIPT_NAME']);
        
        return $protocol . $_SERVER['HTTP_HOST'] . $rootURL;
    }
    
    /**
     * Set the configuration file into $_configuration
     * 
     * @param type $file JSON configuration data file
     * @return void
     */
    public static function loadConfigFile($file) {
        if(file_exists($file)) {
            $content = file_get_contents($file);
            $tmpConfig = json_decode($content, true);
            static::$_configuration = static::_arrayKeyLowerCase($tmpConfig);
        }
        else {
            http_response_code(500);
            header($_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error!", true, 500);
            echo "Application configuration file is not found!";
            die;
        }
    }
    
    /**
     * Lowercase all array keys. It makes the 
     * configuration file case insensitive.
     * 
     * @param array $array Input array
     * @return array
     */
    private static function _arrayKeyLowerCase($a) {
        return array_map(
                    function($i) {
                        if(is_array($i)) {
                            $i = static::_arrayKeyLowerCase($i);
                        }
                        return $i;
                    }, array_change_key_case($a, CASE_LOWER)
                );
    }
    
    /**
     * Return a value from the configuration array
     * 
     * @param string $key Parent configuration key
     * @param string $sub_key Child configuration key
     * @return string
     * @throws ConfigErrorException
     */
    public static function getConfigValue($key, $sub_key) {
        if( isset(static::$_configuration[$key][$sub_key]) ) {
            return static::$_configuration[$key][$sub_key];
        }
        else {
            throw new ConfigException("Configuration value does not exist: [$key][$sub_key]", "error");
        }
    }    
}
