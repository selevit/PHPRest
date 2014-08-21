# PHPRest

This is tornado-like wrapper for creating HTTP RESTful services.

## Example

```php
<?php

require_once "HttpRouter.class.php";
require_once "AjaxHandler.class.php";


$handlers = array(
    array("/^\/foo\/bar\/$/", "\\FooBarHandler"),
    array("/^\/foo\/baz\$/", "\\FooBazHandler"),
    array("/^\/Example\.php\/news\/(.*)$/", "\\MyNewsHandler"),
);


class MyNewsHandler extends PHPRest\AjaxHandler
{
    public function get($news_id)
    {
        $page = $this->getQueryParams("page");
        $this->write(array("page", $page));
    }
}


class FooBarHandler extends PHPRest\AjaxHandler
{
    public function get() 
    {
        $this->write(array("This is my response"));
    }
}


class FooBazHandler extends PHPRest\AjaxHandler
{
    public function get() 
    {
        $this->response->setStatus(200);
        $this->response->setBody("This is my response 2");
        $this->response->write();
    }
}

$router = new PHPRest\HttpRouter($handlers);
$router->initHandler();
```
