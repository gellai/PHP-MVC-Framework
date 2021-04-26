<?php
namespace Database\Exceptions;

use Core\Log;

/**
 * Engine exception extends PHP Exception platform
 */
class EngineException extends \Exception
{
    /**
     * Class constructor
     * 
     * @param string $message Exception message
     * @param string $level Exception level
     * @return void
     */
    public function __construct($message, $level = "info") {
        parent::__construct($message, 500);
        Log::add($message, $level);        
    }   
}