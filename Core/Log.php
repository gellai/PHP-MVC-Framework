<?php
namespace Core;

use Configuration\Config;
use Core\Exceptions\LogException;

/**
 * Application logging class
 */
class Log
{
    /**
     * Log file location: Logs/application.log
     * 
     * @param string $message Log message
     * @param string $level Log level (info, debug, warning, error)
     * @return void
     */
    public static function add($message, $level = "info") {
        if(Config::getConfigValue("app", "logging") !== "true") {
            return;
        }
        
        $logFile = null;
        $traces = debug_backtrace();
        
        try {
            $logFile = fopen(Config::getBasePath() . '/Logs/application.log', 'a');
            $logMessage = "[" . date('d-M-Y H:i:s e') . "]"
                          . " [" . $level . "]\t\t"
                          . $message . "\n";
            $i = 0;
            while($i < count($traces)) {
                    if(isset($traces[$i]['file']) && isset($traces[$i]['line'])) {
                        $logMessage .= "\t\t#" . $i . ":" . "\t" . $traces[$i]['file'] . ":" . $traces[$i]['line'] . "\n";
                    }
                    $logMessage .= isset($traces[$i]['function']) ? "\t\t\tFunction: " . $traces[$i]['function'] . "\n" : ""; 
                    $logMessage .= isset($traces[$i]['class']) ? "\t\t\tClass: " . $traces[$i]['class'] . "\n" : ""; 
                    $i++;
            }        
            fwrite($logFile, $logMessage); 
        }
        catch (LogException $e) {
            dumpr($e->getMessage());
        }
        finally {
            if($logFile != null) {
                fclose($logFile);
            }
        }  
    }
}
