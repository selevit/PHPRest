<?php

namespace PHPRest\Http;

use PHPRest\Http\HeaderList;
use PHPRest\Http\Header;

/**
 * Http request
 */
class Request
{
    /**
     * @var string request path
     */
    protected $path;

    /**
     * @var string request protocol
     */
    protected $proto;

    /**
     * @var array HTTP query
     */
    protected $query;

    /**
     * @var array request body
     */
    protected $body;

    /**
     * @var array request cookie
     */
    protected $cookie;

    /**
     * @var HeaderList request headers
     */
    protected $headers;

    /**
     * @var string HTTP header name
     */
    protected $method;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->headers = new HeaderList();
    }

    /**
     * Parse HTTP request header list
     *
     * @param  array  $headers list of request headers
     *
     * @return Request current object
     */
    public function parseHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->headers->set($name, $value);
        }
        return $this;
    }

    /**
     * Parse global server object
     *
     * @param array $server superglobal $_SERVER array
     *
     * @return Request current object
     */
    public function parse(array $server)
    {
        $this->path = explode('?', $server['REQUEST_URI'], 2);
        parse_str($server['QUERY_STRING'], $this->query);
        $this->method = $server["REQUEST_METHOD"];
        return $this;
    }

    /**
     * Decode request body from raw input
     *
     * @param string $input data from php://input
     *
     * @return Request current object
     */
    public function decodeBody($input)
    {
        parse_str($input, $this->body);
        return $this;
    }

    /**
     * Set files array
     *
     * @param array|null $files global $_FILES array
     *
     * @return Request current object
     */
    public function setFiles($files)
    {
        $this->files = $files;
        return $this;
    }

    /**
     * Set cookie array
     *
     * @param array $cookie global $_COOKIE array
     *
     * @return Request текущий объект
     */
    public function setCookie(array $cookie)
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * Get HTTP method name
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }
}
