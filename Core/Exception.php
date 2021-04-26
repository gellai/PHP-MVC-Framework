<?php
namespace Core;

use Core\View;

/**
 * Exception handling class
 */
class Exception
{
    /**
     * Render the page error model
     * 
     * @param object $exception
     * @return void
     */    
    public static function exceptionHandler($exception) {
        $controllerObj = new PageError();

        if($exception->getCode() == 0) {
            dumpr($exception);
            die;
        }
        else if($exception->getCode() == 200) {
            // If header set to 200 (OK) in exception do nothing.
        }
        else if($exception->getCode() == 404) {
            http_response_code(404);
            header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found!", true, 404);
            View::render('404', $controllerObj);
        }
        else if($exception->getCode() == 500) {
            http_response_code(500);
            header($_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error!", true, 500);
            View::render('500', $controllerObj);
        }
        else {
            http_response_code($exception->getCode());
            View::render('response', $controllerObj);
        }        
    }
}

/**
 * Extend the controller class to render page errors
 */
class PageError extends \Core\Controller {
    protected function after(): void {}
    
    protected function before(): void {} 
}
