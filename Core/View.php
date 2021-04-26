<?php
namespace Core;

use Configuration\Config;
use Core\Exceptions\ViewException;

/**
 * Core view model class
 */
class View
{
    /**
     *
     * @var object The calling control object instance
     */
    private static $_this;
    
    /**
     * 
     * @var string Container of the skeleton HTML code
     */
    private static $_layoutHTML = "";

    /**
     *
     * @var string Website page header title 
     */
    private static $_pageTitle = "";
    
    /**
     *
     * @var string Website page meta keywords 
     */
    private static $_metaKeywords = "";    

    /**
     *
     * @var string Website page meta description 
     */
    private static $_metaDescription = "";   
    
    /**
     * Render the phtml template file
     * 
     * @param string $template Controller/action template file path
     * @return void
     */
    public static function render($template, $controllerObj) {
        static::$_this = $controllerObj;
        
        $viewPath = Config::getBasePath() . '/App/Views/' . $template . '.phtml';
        
        if(is_file($viewPath)) {
            static::_setLayout();
            
            // Run any PHP code and set the final 
            // string into a variable
            ob_start();
                include $viewPath;
                $viewContent = ob_get_contents();
            ob_end_clean();
            
            // Merge layout template and requested view
            $htmlPage = str_replace("{{%VIEWBLOCK%}}", $viewContent, static::$_layoutHTML);
            
            // Display HTML
            echo $htmlPage;
            
            static::$_layoutHTML = "";
        }
        else {
            throw new ViewException("Internal server error! File '$viewPath' is not found!", "error");
        }
    }
    
    /**
     * Reads the skeleton HTML layout so the header 
     * is only stored in 'layout.phtml'
     * 
     * @return void
     */
    private static function _setLayout() {
        $layoutPath = Config::getBasePath() . '/App/Views/layout.phtml';
        
        // It will run any PHP code in the layout template
        ob_start();
            include $layoutPath;
            static::$_layoutHTML = ob_get_contents();
        ob_end_clean();
    }

    /**
     * Create the HTML CSS URL links for the header with
     * all the .css files in assets/css/ directory
     * 
     * @return string Stylesheet link html 
     */
    public static function getStyleSheetHTML() {
        $cssHTML = "";
        $cssPath = Config::getBasePath() . '/assets/css';
        $cssFiles = glob($cssPath . '/' . '*.css');
        
        foreach($cssFiles as $filePath) {
            $cssURL = Config::getUrl() . str_replace(Config::getBasePath(), "", $filePath);
            $cssHTML .= '<link rel="stylesheet" href="' . $cssURL . '?v=' . filemtime($filePath) . '" type="text/css">' . PHP_EOL;
        }
        
        return $cssHTML;
    }
    
    /**
     * Create the HTML JavaScript URL sources for the header
     * with all the .js files in assets/js/ directory
     * 
     * @return string Javascript source html
     */
    public static function getJavaScriptHTML() {
        $jsHTML = "";
        $jsPath = Config::getBasePath() . '/assets/js';
        $jsFiles = glob($jsPath . '/' . '*.js');
        
        foreach($jsFiles as $filePath) {
            $jsURL = Config::getUrl() . str_replace(Config::getBasePath(), "", $filePath);
            $jsHTML .= '<script src="' . $jsURL . '?v=' . filemtime($filePath) . '" type="text/javascript"></script>' . PHP_EOL;
        }        
        
        return $jsHTML;
    }
    
    /**
     * Return the full URL path of the Image folder
     * 
     * @param string $imgFile Image file name (optional)
     * @return string Image source URL
     */
    public static function getImageUrl($imgFile = null) {
        return Config::getUrl() . "/assets/images/" . $imgFile;
    }
    
    /**
     * Set a page's title
     * 
     * @param string $title
     * @return void
     */    
    public static function setPageTitle($title) {
        static::$_pageTitle = $title;
    }

    /**
     * Return the page's title and handles page errors
     * 
     * @return string
     */
    public static function getPageTitle() {
        $title = "";
        
        switch(http_response_code()) {
            case 200:
                $title = static::$_pageTitle;
                break;    
            case 404:
                $title = "404 Page Not Found!";
                break;
            case 500:
                $title = "500 Internal Server Error!";
                break;
            default:
                $title = "Server Response Code " . http_response_code();
                break;
        }
        
        return $title;
    }    
     
    /**
     * Set meta keywords
     * 
     * @param string $keywords
     * @return void
     */
    public static function setMetaKeywords($keywords) {
        static::$_metaKeywords = $keywords;
    }
    
    /**
     * Return meta keywords
     * 
     * @return string
     */
    public static function getMetaKeywords() {
        return static::$_metaKeywords;
    }
  
    /**
     * Set meta description
     * 
     * @param string $desc
     * @return void
     */
    public static function setMetaDescription($desc) {
        static::$_metaDescription = $desc;
    }
    
    /**
     * Return meta description
     * 
     * @return string
     */
    public static function getMetaDescription() {
        return static::$_metaDescription;
    }    
}
