<?php

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
        $this->response->setHeader(
            "Content-type", "application/json; charset=UTF-8");
        parent::write($this->serialize($data));
    }
}
