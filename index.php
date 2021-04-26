<?php
define("__DS__", DIRECTORY_SEPARATOR);
define("__CONFIG_FILE__", __DS__ . "Configuration" . __DS__ . "Application.config");

error_reporting(E_ALL);
    
include 'External' . __DS__ . 'dumpr.php';
require_once 'application.inc.php';
    
use Configuration\Config;
use Core\Helper;

Config::setBasePath(__DIR__);
Config::loadConfigFile(
    Config::getBasePath() . __CONFIG_FILE__
);

Helper::setFormKey();

set_exception_handler('Core\Exception::exceptionHandler');

$router = new Core\Router;
$router->dispatch(
    htmlspecialchars($_SERVER['QUERY_STRING'], ENT_QUOTES)
);
?>