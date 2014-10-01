<?php

namespace PHPRest;

require_once dirname(__FILE__) . "/HttpHandler.class.php";

abstract class AjaxHandler extends HttpHandler
{
    protected function serialize($data, $pretty_print=false)
    {
        $opts = null;
        if ($pretty_print)
            $opts = JSON_PRETTY_PRINT;
        return json_encode($data, $opts);
    }

    protected function write($data)
    {
        if (!$this->response->hasHeader('Content-Type'))
            $this->response->setHeader(
                "Content-type", "application/json; charset=UTF-8");
        parent::write($this->serialize($data));
    }

    protected function writeErrors($status=400)
    {
        $this->response->setStatus($status);
        $this->write($this->getErrors());
    }

    protected function setError($key, $val)
    {
        $this->errors[$key] = $val;
    }

    protected function getErrors()
    {
        return $this->errors;
    }

    private $errors = array();
}
