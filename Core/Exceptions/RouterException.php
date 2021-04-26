<?php
namespace Core\Exceptions;

use Core\Log;

/**
 * Router exception extends PHP Exception platform
 */
class RouterException extends \Exception
{
    /**
     * Class constructor
     * 
     * @param string $message Exception message
     * @param string $level Exception level
     * @return void
     */
    public function __construct($message, $level = "info") {
        parent::__construct($message, 404);
        Log::add($message, $level);
    }     
}
