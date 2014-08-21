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

    private $headers;
}
