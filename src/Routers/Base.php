<?php

namespace PHPRest\Routers;

use PHPRest\Http\Response;
use PHPRest\Http\Request;
use LengthException;
use LogicException;
use UnexpectedValueException;

/**
 * Base router
 */
class Base
{
    /**
     * Constructor
     *
     * @param array $handlers array with resource and handlers map
     */
    public function __construct(array $handlers = array())
    {
        if (!empty($handlers)) {
            $this->setHandlers($handlers);
        }
    }

    /**
     * Set handlers for the router
     *
     * @param array $handlers array with resource and handlers map
     * (format: array('[regex]' => 'Handler\Class\Name')
     *
     * @throws LengthException if handlers is empty
     * @throws LengthException if length of the single handler is invalid
     * @throws UnexpectedValueException if elements of the single
     * handlers is not a strings
     * @throws LogicException if handler class not exists
     * @throws UnexpectedValueException if handler is not subclass of
     * Http\Handler
     */
    public function setHandlers(array $handlers)
    {
        if (!count($handlers)) {
            throw new LengthException("count(handlers) == 0");
        }
        foreach ($handlers as $handler) {
            if (count($handler) !== 2) {
                throw new LengthException(
                    "handler array must have 2 elements"
                );
            )
            if (!is_string($handler[0])) {
                throw new UnexpectedValueException(
                    "handler[0] must be a string"
                );
            }
            if (!is_string($handler[1])) {
                throw new UnexpectedValueException(
                    "handler[1] must be a string"
                );
            }
            $class_name = $handler[1];
            if (!class_exists($class_name)) {
                throw new LogicException(
                    "class '$class_name' not exists"
                );
            }
            if (!is_subclass_of($class_name, '\PHPRest\Http\Handler')) {
                throw new \UnexpectedValueException(
                    "class '$class_name' must be a subclass of PHPRest\Http\Handler"
                );
            }
        }
        $this->handlers = $handlers;
    }

    /**
     * Run processing of the request
     *
     * @param string $path request path
     *
     * @throws LogicException if not handlers found
     */
    public function processRequest($path)
    {
        if (!$this->isInitialized()) {
            throw new LogicException("No handlers found");
        }

        $handler_class = null;
        $handler_args = array();
        $response = new Response();

        foreach ($this->handlers as $handler) {
            $matches = array();
            $match = @preg_match($handler[0], $path, $matches);

            if ($match === false) { // is not regex
                if ($handler[0] == $path) {
                    $handler_class = $handler[1];
                    break;
                }
            } elseif ($match) {
                $handler_class = $handler[1];
                $args_count = count($matches) - 1;
                if ($args_count > 0) {
                    for ($i = 1; $i < $args_count + 1; $i++) {
                        $handler_args[] = $matches[$i];
                    }
                }
                break;
            }
        }

        if (!$handler_class) {
            return $response->flushError(404);
        }

        $handler = new $handler_class($this->response);
        $method = strtolower($this->request->getMethod());

        if (!method_exists($handler, $method)) {
            return $response->flushError(501); // Not implemented
        }

        $response = call_user_func_array(
            array($handler, $method),
            $handler_args
        );

        if (!$response instanceof Response) {
            throw new UnexpectedValueException(
                "Handler method must returns the Response object
            ");
        }

        $response->write();
    }

    /**
     * Получить объект запроса клиента
     *
     * @return Request
     */
    public function getRequestObject()
    {
        $request = new Request();
        $request->parse($_SERVER)
            ->decodeBody(file_get_contents("php://input"))
            ->setFiles($_FILES)
            ->setCookie($_COOKIE);
        return $request;
    }

    /**
     * Process current request
     */
    public function run()
    {
        $path = explode("?", $_SERVER["REQUEST_URI"], 2);
        $this->processRequest($path[0]);
    }

    /**
     * Check if object is initialized
     *
     * @return bool
     */
    public function isInitialized()
    {
        return is_array($this->handlers) && count($this->handlers) !== 0;
    }

    private $response;
    private $handlers;
}
