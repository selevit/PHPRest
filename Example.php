<?php

namespace PHPRest\Example;

require __DIR__ . '/bootstrap.php';

use PHPRest;
use PHPRest\Http\Handler;
use PHPRest\Http\JSONResponse;

$routes = array(
    array('/ajax/register/', __NAMESPACE__ . '\RegisterHandler'),
    array('#^/product/([0-9]+)/reviews/$', __NAMESPACE__ . '\RegisterHandler'),
    array('#^/Example.php/news/(.*)$#', __NAMESPACE__ . '\NewsHandler'),
);

/**
 * All handlers must be subclass of PHPRest\HttpHandler
 */
class RegisterHandler extends Handler
{
    /**
     * Handle POST requests
     */
    public function post()
    {
        // Get POST params
        $login = $this->request->body->param("login");
        $password = $this->request->body->param("password");

        $params = $this->request->body->all();
        $some_params = $this->request->body->params(
            array("login", "password")
        );

        $some_params = $this->request->body->params("login", "password");

        $some_params = $this->request->body->params(
            array("password", null),
            array("email", "default@mail.com"),
            array("username", "default username")
        );

        // Get URL-query params
        $redirect_url = $this->request->query->get("next", "/default-url");
        $response = new JSONResponse();

        // Check form fields
        if (!$login) {
            $response->setFieldError("login", "Required field");
        }
        if (!$password) {
            $response->setFieldError("password", "Required field");
        }

        if ($response->hasErrors()) {
            $response->writeAllErrors();
            return $response;
        }

        // Do other stuff...

        $response->setData(array("user_id" => 100));
        return $response;
    }
}

/**
 * Product reviews handler
 */
class ProductReviewHandler extends Handler
{
}

/**
 * News handler
 */
class NewsHandler extends Handler
{
}

// Initialize HTTP router
$router = new PHPRest\Router($routes);
$router->run();
