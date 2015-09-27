<?php

namespace PHPRest\Http;

use InvalidArgumentException;

/**
 * HTTP header
 */
class Header
{
    /**
     * Constructor
     *
     * @param string $name name the header
     * @param string $value value header
     */
    public function __construct($name, $value)
    {
        $this->setName($name);
        $this->setValue($value);
    }

    /**
     * Get name of header
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name of header
     *
     * @param string $value header name
     */
    public function setName($value)
    {
        if (!is_string($value) || empty($value)) {
            throw new InvalidArgumentException(
                'header name must be a non-empty string'
            );
        }
        $this->name = $value;
    }

    /**
     * Get value of header
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value of header
     *
     * @param string $value header value
     */
    public function setValue($value)
    {
        if (!is_string($value) || empty($value)) {
            throw new InvalidArgumentException(
                'header value must be a non-empty string'
            );
        }
        $this->value = $value;
    }

    /**
     * Convert header object to string representation
     */
    public function toString()
    {
        return $this->name . ': ' . $this->value;
    }

    /**
     * Write header to STDOUT
     */
    public function write()
    {
        header($this->toString());
    }

    /**
     * @var string name of header
     *
     * For example: Content-type
     */
    private $name;

    /**
     * @var string value of header
     *
     * For example: text/html; charset=utf-8
     */
    private $value;
}
