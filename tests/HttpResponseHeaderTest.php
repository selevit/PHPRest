<?php

namespace PHPRest;

require_once dirname(__FILE__) . "/../HttpResponseHeader.class.php";

class HttpResponseHeaderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->header = new HttpResponseHeader("Content-Type", "text/html");
    }

    public function testGetKey()
    {
        $this->header->setKey("Test key");
        $this->assertEquals($this->header->getKey(), "Test key");
    }

    public function testValKey()
    {
        $this->header->setVal("Test value");
        $this->assertEquals($this->header->getVal(), "Test value");
    }

    public function testSetKey()
    {
        $this->header->setKey("The new key of header");
        $this->assertEquals($this->header->getKey(), "The new key of header");

        try {
            $this->header->setKey("");
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), "header key must be a non-empty string");
        }
        try {
            $this->header->setKey(array());
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), "header key must be a non-empty string");
        }
    }

    public function testSetVal()
    {
        $this->header->setVal("The new value of header");
        $this->assertEquals($this->header->getVal(), "The new value of header");

        try {
            $this->header->setVal("");
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), "header value must be a non-empty string");
        }
        try {
            $this->header->setVal(array());
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), "header value must be a non-empty string");
        }
    }

    public function testToString()
    {
        $this->header->setKey("Content-type");
        $this->header->setVal("text/html; charset=UTF-8");
        $this->assertEquals($this->header->toString(),
            "Content-type: text/html; charset=UTF-8");
    }

    private $header;
}
