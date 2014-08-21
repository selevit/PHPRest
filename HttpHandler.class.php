<?php

namespace PHPRest;

require_once dirname(__FILE__) . "/HttpResponse.class.php";

abstract class HttpHandler
{
    final public function __construct($response)
    {
        if (!$response instanceof HttpResponse)
            throw new \InvalidArgumentException(
                "response must be instance of HttpResponse");
        $this->response = $response;
        $this->req_query = $_GET;
        $this->req_body = $_POST;
    }

    public function get()
    {
        $this->response->writeError(405);
    }

    public function post()
    {
        $this->response->writeError(405);
    }

    public function put()
    {
        $this->response->writeError(405);
    }

    public function delete()
    {
        $this->response->writeError(405);
    }

    public function head()
    {
        $this->response->writeError(405);
    }

    public function patch()
    {
        $this->response->writeError(405);
    }

    public function trace()
    {
        $this->response->writeError(405);
    }

    public function connect()
    {
        $this->response->writeError(405);
    }

    public function options()
    {
        $this->response->writeError(405);
    }

    /**
     * Отправить ответ с данными клиенту
     * Alias для HttpResponse::write()
     * @param  string $data тело ответа
     */
    protected function write($data)
    {
        $this->response->write($data); 
    }

    /**
     * Получить параметры строки запроса
     * @param  string $name имя параметра 
     * @return array
     */
    protected function getQueryParams($name)
    {
        return self::getParams($this->req_query, $name);
    }

    /**
     * Получить параметр строки запроса
     * @param  string $name имя параметра 
     * @return string|null
     */
    protected function getQueryParam($name)
    {
        $params = $this->getQueryParams($name);
        $count = count($params);
        if (!$count)
            return null;
        return trim($params[$count-1]);
    }

    /**
     * Получить параметры тела запроса
     * @param  string $name имя параметра 
     * @return array
     */
    protected function getBodyParams($name)
    {
        return self::getParams($this->req_body, $name);
    }

    /**
     * Получить параметр тела запроса
     * @param  string $name имя параметра 
     * @return string|null
     */
    protected function getBodyParam($name)
    {
        $params = $this->getBodyParams($name);
        $count = count($params);
        if (!$count)
            return null;
        return trim($params[$count-1]);
    }

    private static function getParams(array &$req, $name)
    {
        if (array_key_exists($name, $req))
            if (is_array($req[$name]))
                return $req[$name];
            else if (is_string($req[$name]))
                return array($req[$name]);
        return array();
    }

    protected $response;
    private $req_query = array();
    private $req_body = array();
}
