<?php

namespace PHPRest;

require_once dirname(__FILE__) . "/HttpResponseHeaderList.class.php";

class HttpResponse
{
    public function __construct($body="", $status=200)
    {
        $this->body = $body;
        $this->status = $status;
        $this->headers = new HttpResponseHeaderList($headers);
        $this->protocol = self::getServerProtocol();
    }

    /**
     * Установить код ответа сервера
     * @param integer $value код HTTP-ответа
     */
    public function setStatus($value)
    {
        if (!is_integer($value))
            throw new \InvalidArgumentException("value must be integer");
        if ($value <= 0 || $value >= 600)
            throw new \OutOfRangeException("value must be > 0 and < 600");
       $this->status = $value; 
    }

    /**
     * Получить код ответа сервера
     * @return integer код HTTP-ответа
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Установить тело ответа
     * @param string $value строка с телом ответа
     */
    public function setBody($value)
    {
        if (!is_string($value))
            throw new \InvalidArgumentException("body must be a string");
        $this->body = $value;
    }

    /**
     * Получить тело ответа сервера
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Установить HTTP-заголовок ответа
     * @param string $key ключ
     * @param string $val значение
     */
    public function setHeader($key, $val)
    {
        return $this->headers->set($key, $val);
    }

    /**
     * Получить значение HTTP-заголовка
     * @param  string $key ключ
     * @return string
     */
    public function getHeader($key)
    {
        return $this->headers->get($key);
    }

    /**
     * Проверить, установлен ли хедер
     * @param $key ключ
     * @return boolean
     */
    public function hasHeader($key)
    {
        return $this->headers->has($key);
    }

    /**
     * Получить HTTP-заголовки
     * @return HttpResponseHeaders заголовки ответа
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    public static function getHttpResponseText($status)
    {
        $text = null;
        switch($status) {
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
        if (!$text)
            throw new \UnexpectedValueException("Unknown http status code");
        return $text;
    }

    public static function getServerProtocol()
    {
        if (isset($_SERVER["SERVER_PROTOCOL"]))
            return $_SERVER["SERVER_PROTOCOL"];
        return "HTTP/1.0";
    }

    public function write($body=null)
    {
        if ($this->isFinished())
            throw new \LogicException("HTTP response already finished");
        if ($body)
            $this->setBody($body);
        header($this->getHttpStatusString());
        $this->headers->write();
        echo $this->body;

        $this->finish();
    }

    public function writeError($status)
    {
        $this->setStatus($status);
        $body = sprintf("<html><h1>%s %s</h1></html>", $status,
            self::getHttpResponseText($status));
        $this->setBody($body);
        $this->setHeader("Content-Type", "text/html; charset=UTF-8");
        $this->write();
    }

    public function isFinished()
    {
        return $this->is_finished;
    }

    public function finish()
    {
        if ($this->is_finished)
            throw new \LogicException("HTTP response already finished");
        $this->is_finished = true;
    }

    public function getRequestMethod()
    {
        if (empty($_SERVER["REQUEST_METHOD"]))
            return "GET";
        return $_SERVER["REQUEST_METHOD"];
    }

    private function getHttpStatusString() 
    {
        $text = self::getHttpResponseText($this->status);
        $protocol = $this->protocol;
        return "$protocol ". $this->status . " $text";
    }

    private $headers;
    private $is_finished = false;
    private $status = 200;
    private $body = "";
    private $protocol;
}
