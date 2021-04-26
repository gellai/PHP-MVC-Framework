<?php
namespace Configuration\Exceptions;

use Core\Log;

/**
 * Configuration exception extends PHP Exception platform
 */
class ConfigExcetion extends \Exception
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

