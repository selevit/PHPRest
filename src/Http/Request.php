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
     * Constructor
     */
    public function __construct()
    {
        $headers = \http_get_request_headers();
        $this->headers = new HeaderList();
        foreach ($headers as $header) {
            $parts = implode(':', $header);
            $this->headers->set(trim($parts[0]), trim($parts[1]));
        }
        $this->parse($_SERVER);
        $this->decodeBody(\file_get_contents('php://input'));
        $this->setFiles($_FILES);
        $this->setCookie($_COOKIE);
    }

    /**
     * Parse global server object
     *
     * @return Request текущий объект
     */
    public function parse(array $server)
    {
        $this->path = explode('?', $server['REQUEST_URI'], 2);
        parse_str($server['QUERY_STRING'], $this->query);
        return $this;
    }

    /**
     * Decode request body from raw input
     *
     * @param string $input data from php://input
     *
     * @return Request текущий объект
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
     * @return Request текущий объект
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
}
