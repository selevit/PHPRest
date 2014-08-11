<?php

require_once dirname(__FILE__) . "/../HttpResponse.class.php";

class HttpResponseTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->response = new HttpResponse();
    }

    public function testSetStatus()
    {
       $this->response->setStatus(404); 
       $this->assertEquals($this->response->getStatus(), 404);
       try {
           $this->response->setStatus("foo");
       } catch (InvalidArgumentException $e) {
           $message = $e->getMessage();
           $this->assertEquals($message, "value must be integer");
       }
       try {
           $this->response->setStatus(-10);
       } catch (OutOfRangeException $e) {
           $message = $e->getMessage();
           $this->assertEquals($message, "value must be > 0 and < 600");
       }
       try {
           $this->response->setStatus(600);
       } catch (OutOfRangeException $e) {
           $message = $e->getMessage();
           $this->assertEquals($message, "value must be > 0 and < 600");
       }
    }

    public function testGetStatus()
    {
        $this->response->setStatus(500);
        $this->assertEquals($this->response->getStatus(), 500);
    }

    public function testSetBody()
    {
        $this->response->setBody("My response body");
        $this->assertEquals($this->response->getBody(), "My response body");

        $this->response->setBody("");
        $this->assertEquals($this->response->getBody(), "");

        try {
            $this->response->setBody(100);
        } catch (InvalidArgumentException $e) {
            $this->assertEquals($e->getMessage(), "body must be a string");
        }
    }

    public function testGetBody()
    {
        $this->response->setBody("New test body");
        $this->assertEquals($this->response->getBody(), "New test body");
    }

    private $response;
}
