<?php

namespace PHPRest;

require_once dirname(__FILE__) . "/../HttpResponseHeaderList.class.php";

class HttpResponseHeaderListTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->headers = new HttpResponseHeaderList();
    }

    public function testAdd()
    {
        $this->headers->add("Content-type", "application/json");
        $header = $this->headers->get("Content-Type");
        $this->assertInstanceOf("PHPRest\HttpResponseHeader", $header);
        $this->assertEquals($header->getVal(), "application/json");
    }

    public function testRemove()
    {
        $this->headers->add("Content-Length", "339393");
        $this->headers->remove("Content-length");
        $this->assertFalse($this->headers->has("Content-Length"));
    }

    public function testGet()
    {
        $this->headers->set("Accept-Encoding", "gzip,deflate");
        $header = $this->headers->get("accept-encoding");
        $this->assertInstanceOf("PHPRest\HttpResponseHeader", $header);
        $this->assertEquals($header->getVal(), "gzip,deflate");
    }

    public function testSet()
    {
        $this->headers->set("Content-Type", "text/html");
        $this->assertTrue($this->headers->has("content-type"));
        $this->assertEquals($this->headers->get("content-type")->getVal(),
            "text/html");
        $this->headers->set("content-type", "text/plain");
        $this->assertEquals($this->headers->get("content-type")->getVal(),
            "text/plain");
    }

    public function testHas()
    {
        $this->headers->set("Content-Type", "text/html");
        $this->assertTrue($this->headers->has("content-type"));
        $this->assertTrue($this->headers->has("Content-Type"));
        $this->assertTrue($this->headers->has("CONTENT-TYPE"));
        $this->assertFalse($this->headers->has("__CONTENT-TYPE"));
    }

    private $headers;
}
