<?php

namespace PHPRest\Http\Header;

use LogicException;
use OutOfBoundsException;
use ArrayObject;

/**
 * HTTP header list
 */
class HeaderList
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->headers = new ArrayObject();
    }

    /**
     * Add header to list
     *
     * @param string $name header name
     * @param string $value header value
     *
     * @throws LogicException if header already exists
     */
    public function add($name, $value)
    {
        if ($this->has($name)) {
            throw new LogicException("header '${key}' already exists");
        }
        $this->headers[] = new Header($name, $value);
    }

    /**
     * Remove header
     *
     * @param string $name header name
     *
     * @throws LogicException if header not exists
     */
    public function remove($name)
    {
        $index = $this->_index($name);
        if ($index === -1) {
            throw new LogicException("header '$name' does not exists");
        }
        unset($this->headers[$index]);
    }

    /**
     * Get header by name
     *
     * @param string $name header name
     *
     * @throws OutOfBoundsException if header not found
     *
     * @return Header HTTP header
     */
    public function get($name)
    {
        $index = $this->_index($name);
        if ($index === -1) {
            throw new OutOfBoundsException("No key found: $name");
        }
        return $this->headers[$index];
    }

    /**
     * Set header name
     *
     * @param string $name name of header
     * @param string $value value of header
     */
    public function set($name, $value)
    {
        $index = $this->_index($name);
        $header = new Header($name, $value);
        if ($index === -1) {
            $this->headers[] = $header;
        } else {
            $this->headers[$index] = $header;
        }
    }

    /**
     * Check header is exists
     *
     * @param string $name header name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->_index($name) !== -1;
    }

    /**
     * Get string representation of the header list
     *
     * @return string
     */
    public function toString()
    {
        $result = "";
        foreach ($this->headers as $header) {
            $result .= $header->toString() . "\r\n";
        }
        return $result;
    }

    /**
     * Get index of the header by name
     *
     * @param string $name header name
     *
     * @return int positive array index or -1 (if header not found)
     */
    private function _index($name)
    {
        $count = count($this->headers);
        for ($i = 0; $i < count($this->headers); $i++) {
            $header = $this->headers[$i];
            $current_key = $header->getKey();
            if (strtolower($current_key) === strtolower($key)) {
                return $i;
            }
        }
        return -1;
    }

    /**
     * @var ArrayObject list ob header objects
     */
    private $headers;
}
