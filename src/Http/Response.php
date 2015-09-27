<?php

namespace PHPRest\Http;

use InvalidArgumentException;
use UnexpectedValueException;
use OutOfRangeException;
use LogicException;

/**
 * HTTP response
 */
class Response
{
    /**
     * Constructor
     *
     * @param string $body response body
     * @param int $status response status
     */
    public function __construct($body = "", $status = 200)
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = new HeaderList();
        $this->proto = self::getServerProto();
    }

    /**
     * Set HTTP response code
     *
     * @param int $value HTTP status code
     *
     * @throws InvalidArgumentException if value is not integer
     * @throws OutOfRangeException if status value is out of range
     */
    public function setStatus($value)
    {
        if (!is_integer($value)) {
            throw new InvalidArgumentException("value must be integer");
        }
        if ($value <= 0 || $value >= 600) {
            throw new OutOfRangeException("value must be > 0 and < 600");
        }
       $this->status = $value;
    }

    /**
     * Get HTTP response status
     *
     * @return int HTTP status code
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set response body
     *
     * @param string $value body of response
     *
     * @throws InvalidArgumentException if body is not a string
     */
    public function setBody($value)
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException("body must be a string");
        }
        $this->body = $value;
    }

    /**
     * Get response body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set HTTP header
     *
     * @param string $name header name
     * @param string $value header value
     */
    public function setHeader($name, $value)
    {
        return $this->headers->set($name, $value);
    }

    /**
     * Get HTTP header value
     *
     * @param string $name header name
     *
     * @return Header
     */
    public function getHeader($name)
    {
        return $this->headers->get($name);
    }

    /**
     * Check if header exists
     *
     * @param $name header name
     *
     * @return boo
     */
    public function hasHeader($name)
    {
        return $this->headers->has($name);
    }

    /**
     * Get all HTTP  headers
     *
     * @return HeaderList list of a response headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get string representation of HTTP status code
     *
     * @param int status http status code
     *
     * @throws UnexpectedValueException if status code is unknown
     *
     * @return string
     */
    public static function getStatusText($status)
    {
        $text = null;
        switch ($status) {
            case 100: $text = "Continue"; break;
            case 101: $text = "Switching Protocols"; break;
            case 200: $text = "OK"; break;
            case 201: $text = "Created"; break;
            case 202: $text = "Accepted"; break;
            case 203: $text = "Non-Authoritative Information"; break;
            case 204: $text = "No Content"; break;
            case 205: $text = "Reset Content"; break;
            case 206: $text = "Partial Content"; break;
            case 300: $text = "Multiple Choices"; break;
            case 301: $text = "Moved Permanently"; break;
            case 302: $text = "Moved Temporarily"; break;
            case 303: $text = "See Other"; break;
            case 304: $text = "Not Modified"; break;
            case 305: $text = "Use Proxy"; break;
            case 400: $text = "Bad Request"; break;
            case 401: $text = "Unauthorized"; break;
            case 402: $text = "Payment Required"; break;
            case 403: $text = "Forbidden"; break;
            case 404: $text = "Not Found"; break;
            case 405: $text = "Method Not Allowed"; break;
            case 406: $text = "Not Acceptable"; break;
            case 407: $text = "Proxy Authentication Required"; break;
            case 408: $text = "Request Time-out"; break;
            case 409: $text = "Conflict"; break;
            case 410: $text = "Gone"; break;
            case 411: $text = "Length Required"; break;
            case 412: $text = "Precondition Failed"; break;
            case 413: $text = "Request Entity Too Large"; break;
            case 414: $text = "Request-URI Too Large"; break;
            case 415: $text = "Unsupported Media Type"; break;
            case 500: $text = "Internal Server Error"; break;
            case 501: $text = "Not Implemented"; break;
            case 502: $text = "Bad Gateway"; break;
            case 503: $text = "Service Unavailable"; break;
            case 504: $text = "Gateway Time-out"; break;
            case 505: $text = "HTTP Version not supported"; break;
        }
        if (!$text) {
            throw new UnexpectedValueException("Unknown http status code");
        }
        return $text;
    }

    /**
     * Get server protocol type and version
     *
     * @return string
     */
    public static function getServerProto()
    {
        if (isset($_SERVER["SERVER_PROTOCOL"])) {
            return $_SERVER["SERVER_PROTOCOL"];
        }
        return "HTTP/1.0"; // by default
    }

    /**
     * Write response to client
     *
     * @param string body response body
     *
     * @throws LogicException if response already finished
     */
    public function write($body = "")
    {
        if ($this->isFinished()) {
            throw new LogicException("HTTP response already finished");
        }
        if ($body) {
            $this->setBody($body);
        }
        header($this->getStatusString());
        $this->headers->write();
        echo $this->body;
        $this->finish();
    }

    /**
     * Flush error to client
     *
     * @param int status HTTP status code
     */
    public function flushError($status)
    {
        $this->setStatus($status);
        $body = sprintf(
            '<html><h1>%s %s</h1></html>',
            $status,
            self::getStatusText($status)
        );
        $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $this->write($body);
    }

    /**
     * Check if response is finished
     *
     * @return bool
     */
    public function isFinished()
    {
        return $this->is_finished;
    }

    /**
     * Finish response
     *
     * @throws LogicException if response already finished
     */
    public function finish()
    {
        if ($this->is_finished) {
            throw new LogicException("HTTP response already finished");
        }
        $this->is_finished = true;
    }

    /**
     * Get HTTP status string of response
     * Example: HTTP/1.1 200 OK
     *
     * @return string
     */
    private function getStatusString()
    {
        $text = self::getStatusText($this->status);
        return sprintf('%s %s %s', $this->proto, $this->status, $text);
    }

    /**
     * @var HeaderList response headers
     */
    private $headers;

    /**
     * @var ResponseBody response body
     */
    private $body = '';

    private $is_finished = false;
    private $status = 200;
    private $proto;
}
