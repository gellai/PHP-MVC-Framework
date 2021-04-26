<?php
namespace Core;

/**
 * The Helper class contains general methods
 * which can be used anywhere as well as
 * bundles various methods from all over
 * the application under one class.
 */
class Helper
{
    /**
     * Time in seconds after the form key is
     * invalid during inactivity
     */
    const FORMKEY_TIMEOUT = 600;
    
    /**
     * See:
     * \Configuration\Config::getBasePath()
     * 
     * @return string
     */
    public static function getBasePath() {
        return \Configuration\Config::getBasePath();
    }
    
    /**
     * See:
     * \Configuration\Config::getUrl()
     * 
     * @return string
     */
    public static function getUrl() {
        return \Configuration\Config::getUrl();
    }
    
    /**
     * See:
     * \Core\View::getImageUrl($imgFile)
     * 
     * @param string $imgFile
     * @return string
     */
    public static function getImageUrl($imgFile = null) {
        return \Core\View::getImageUrl($imgFile);
    }
    
    /**
     * Set a random key for form validation;
     * the key renews after the set timeout period
     * 
     * @return void
     */
    public static function setFormKey() {
        if(!isset($_SESSION['FORMKEY']) ||
           $_SESSION['FORMKEY_TIMEOUT'] < date("m/d/Y h:i:s")) {
                $_SESSION['FORMKEY_TIMEOUT'] = date("m/d/Y h:i:s", time() + self::FORMKEY_TIMEOUT);
                $_SESSION['FORMKEY'] = md5(static::_generateRandomString());
        }
    }
    
    /**
     * Return the key for form validation;
     * the key renews before the set timeout period
     * 
     * @return string
     */
    public static function getFormKey() {
        $formKey = null;
        
        if( isset($_SESSION['FORMKEY_TIMEOUT']) && isset($_SESSION['FORMKEY']) ) {
            if($_SESSION['FORMKEY_TIMEOUT'] > date("m/d/Y h:i:s")) {
                $formKey = $_SESSION['FORMKEY'];
                $_SESSION['FORMKEY_TIMEOUT'] = date("m/d/Y h:i:s", time() + self::FORMKEY_TIMEOUT);
            }         
        }
        
        return $formKey;
    }
    
    /**
     * Generate a random key for form validation
     * 
     * @param integer $length
     * @return string
     */
    private static function _generateRandomString($length = 6) {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!£$%^&*()@#?/\-+=[]{}";
        $randString = "";
        
        for($i=0; $i < $length; $i++) {
            $randString .= $chars[rand(0, strlen($chars)-1)];
        }
        
        return $randString;
    }
    
    /**
     * Create a Base 64 hash from given array of strings;
     * the sections are separated with ||
     * 
     * @param array $hash
     * @return string
     */
    public static function createB64Hash($hash = array()) {
        $raw = "";
        
        foreach($hash as $item) {
            $raw .= $item . "||";
        }
        
        return base64_encode(
                    substr($raw, 0, -2)
               );
    }
    
    /**
     * Return the value of the HTTP POST method
     * 
     * @param type $string
     * @return string
     */
    public static function getPost($string) {
        $post = filter_input(INPUT_POST, $string, FILTER_SANITIZE_STRING);
        
        return $post;
    }    
}

