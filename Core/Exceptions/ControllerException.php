<?php
namespace Core\Exceptions;

use Core\Log;

/**
 * Controller exception extends PHP Exception platform
 */
class ControllerException extends \Exception
{
    /**
     * Class constructor
     * 
     * @param string $message Exception message
     * @param string $level Exception level
     * @return void
     */
    public function __construct($message, $level = "error") {
        parent::__construct($message, 500);
        Log::add($message, $level);
    }     
}
