<?php
namespace App\Controllers;

use Core\View;
use App\Models\DummyDatabase;

/**
 * Home (Index) controller model class
 */
class HomeController extends \Core\Controller
{  
    /**
     * Database tables model class
     * 
     * @var object 
     */
    public $databaseModelObj;
    
    public function __construct() {
        $this->databaseModelObj = DummyDatabase::getModelInstance();
    }

    /**
     * Home controller's index method
     * 
     * The instance of the current controller class object
     * is passed to the render method of the View class to
     * process any parameters.
     * 
     * @return void
     */
    public function indexAction() {
        View::setPageTitle("PHP MVC Framework Example - gellai.com");
        View::setMetaKeywords("meta, keyword, list");
        View::setMetaDescription("Meta description");
        View::render('Home/index', $this);
    }
    
    /**
     * Before action method
     * 
     * @return void
     */
    protected function before(): void {
        
    } 
    
    /**
     * After action method
     * 
     * @return void
     */
    protected function after(): void {

    }    
}