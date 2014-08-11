<?php

require dirname(__FILE__) . "/HttpResponse.class.php";

class HttpRouter
{
    /**
     * Конструктор класса HttpRouter
     * @param array $handlers массив с именами ресурсов и обработчиков
     */
    public function __construct(array $handlers=array()) 
    {
        if (!empty($handlers))
            $this->setHandlers($handlers);
        $this->response = new HttpResponse();
    }

    /**
     * Установить значение handlers
     * @param array $handlers массив с именами ресурсов и обработчиков
     * в формате array("[regex]" => "Handler\Class\Name")
     */
    public function setHandlers(array $handlers)
    {
        if (!count($handlers))
            throw new LengthException("count(handlers) == 0");
        foreach ($handlers as $handler) {
            if (count($handler) !== 2)
                throw new LengthException(
                    "handler array must have 2 elements");
            if (!is_string($handler[0]))
                throw new UnexpectedValueException(
                    "handler[0] must be a string");
            if (!is_string($handler[1]))
                throw new UnexpectedValueException(
                    "handler[1] must be a string");
            $cls_name = $handler[1];
            if (!class_exists($cls_name))
                throw new LogicException("class `$cls_name` does not exists");
            if (!is_subclass_of($cls_name, "HttpHandler"))
                throw new UnexpectedValueException(
                    "class $cls_name` must be a subclass of AjaxHandler");
        }
        $this->handlers = $handlers;
    }

    /**
     * Запустить обработчик запроса
     * @param  string $path строка запроса
     */
    public function processRequest($path)
    {
        if (!$this->isInitialized())
            throw new LogicException("No handlers found");
        $handler_cls = null;
        $handler_args = array();
        foreach ($this->handlers as $handler) {
            $matches = array();
            $match = @preg_match($handler[0], $path, $matches);
            if ($match === false) { // is not regex
                if ($handler[0] == $path) {
                    $handler_cls = $handler[1];
                    break;
                }
            } else if ($match) {
                $handler_cls = $handler[1];
                $count_args = count($matches) - 1;
                if ($count_args > 0)
                    for ($i = 1; $i < $count_args+1; $i++)
                        $handler_args[] = $matches[$i];
                break;
            }
        }
        if (!$handler_cls) {
            $this->response->writeError(404);
            return;
        }
        $handler = new $handler_cls($this->response);
        $method = mb_strtolower($this->response->getRequestMethod());
        if (!method_exists($handler, $method)) {
            $this->response->writeError(501); // Not implemented
            return;
        }
        call_user_method_array($method, $handler, $handler_args);
    }

    public function initHandler()
    {
        $path = explode("?", $_SERVER["REQUEST_URI"], 2);    
        $this->processRequest($path[0]);
    }

    public function isInitialized()
    {
        return is_array($this->handlers) && count($this->handlers) !== 0;
    }

    private $response;
    private $handlers;
}
