# PHP MVC (Model-View-Controller) Framework

[![N|Gellai](https://www.gellai.com/wp-content/themes/gellai/images/Powered-By-Gellai.png)](https://gellai.com)

## What is this?
An MVC skeleton framework for PHP applications (beta).

Features
- Easily extendable
- Multiple database connection types
- Dynamically loaded CSS style sheets and JavaScripts
- URL masking support
- SEO friendly
- Error logging

URL requests are pointed to controller classes and their methods - action methods.

| URL Request | Controller Class | Action Method | Parameters | Notes |
| ----------- | ---------------- | ------------- | ---------- | ----- |
| www.example.com | HomeController | indexAction | | Default controller/action |
| www.example.com/product | ProductController | indexAction | | Default action method is index |
| www.example.com/product/view | ProductController | viewAction | | Last part of the URL slug is an action method |
| www.example.com/product/view/ | Product/ViewController | indexAction | | Closing '/' will change the action method to a controller |
| www.example.com/product/view?id=2 | ProductController | viewAction | id = 2 | |
| www.example.com/product/view/?id=2 | Product/ViewController | indexAction | id = 2 | |

## Installation
Copy the content on the webserver's public folder.
```
$ git clone git://github.com/gellai/php-mvc-framework.git
```

## Configuration
Database connection and URL rewrites are set in a configuration file which is in JSON format.
```
Configuration/Application.config
```
### Logging
Application logging can be turned on and off.

```json
"app":
        {
            "logging" : "true"
        }
```

```
Logs/application.log
```

### Database
Supported connections: 
- MySQL
- PostgreSQL
- SQLite
- MSSQL

```json
"db":
        {
            "_comment" : "Engine types: MySQL/PostgreSQL/SQLite/MSSQL",
            "engine" : "MySQL",
            "host" : "localhost",
            "port" : "3306",
            "user" : "",
            "password" : "",
            "database" : "",
            "sqlite_file" : "Database/database.sqlite"
        },
```

### URL Rewrites
Custom URLs can be linked to a controller class and its action method without parameters.

```json
"url":
        {
            "start-page" : "home",
            "start-page/add" : "home/add"
        }
```

| Value | URL | Controller Class | Action Method |
| ----- | --- | ---------------- | ------------- |
| "product" : "product" | www.example.com/product | ProductController | indexAction |
| "product/" : "product" | www.example.com/product/ | ProductController | indexAction |
| "product" : "product/view" | www.example.com/product | ProductController | viewAction |
| "product" : "product/view/" | www.example.com/product/ | Product/ViewController | indexAction |

### CSS & JavaScript

Style sheets and JavaScripts are stored within the ```assets/css``` and ```assets/js``` directories. Just simply copy any files to the corresponding directory and it will automaticaly load it to the source code.

### Images

Images are stored within ```assets/images``` directory. The following command will add them to the front page.

```php
<?= static::$_this->helper->getImageUrl('image.jpg') ?>
```

Full technical implementation is coming soon... 
