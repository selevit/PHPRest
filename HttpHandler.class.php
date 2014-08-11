<?php

require_once dirname(__FILE__) . "/HttpResponse.class.php";

abstract class HttpHandler
{
    public function __construct($response)
    {
        if (!$response instanceof HttpResponse)
            throw new InvalidArgumentException(
                "response must be instance of HttpResponse");
        $this->response = $response;
    }

    protected function write($data)
    {
        $this->response->setBody($data);
        $this->response->write($data); 
    }

    public function get()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function post()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function put()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function delete()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function head()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function patch()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function trace()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function connect()
    {
        $this->response->writeError(405); // Method not allowed
    }

    public function options()
    {
        $this->response->writeError(405); // Method not allowed
    }

    protected $response;
}
