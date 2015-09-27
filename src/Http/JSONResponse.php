<?php

namespace PHPRest\Http;

/**
 * JSON HTTP Response
 */
class JSONResponse extends Response
{
    const DEFAULT_ERROR_HTTP_CODE = 400;

    /**
     * Write response to client
     *
     * @param mixed $data JSON serializable object
     *
     * @return void
     */
    public function write($data = "")
    {
        if (!$this->hasHeader('Content-Type') {
            $this->setHeader('Content-Type', 'application/json; charset=UTF-8');
        }
        return parent::write(json_encode($data));
    }

    /**
     * Set field error to response
     *
     * @param string $name error key
     * @param string $value error text
     */
    public function setFieldError($name, $value)
    {
        $this->errors[$name] = $value;
    }

    /**
     * Get all field errors
     *
     * @return array
     */
    public function getFieldErrors()
    {
        return $this->fieldErrors;
    }

    /**
     * Set common (non field) error to response
     *
     * @param string $value error text
     */
    public function setCommonError($value)
    {
        $this->commonErrors[] = $value;
    }

    /**
     * Get all common errors
     *
     * @return array
     */
    public function getCommonErrors()
    {
        return $this->commonErrors;
    }

    /**
     * Check if response has field errors
     *
     * @return bool
     */
    public function hasFieldErrors()
    {
        return !empty($this->fieldErrors);
    }

    /**
     * Check if response has common erros
     *
     * @return bool
     */
    public function hasCommonErrors()
    {
        return !empty($this->commonErrors);
    }

    /**
     * Check if response has any errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        return !$this->hasFieldErrors() || !$this->hasCommonErrors();
    }

    /**
     * Get all (field and common errors) in single object
     *
     * Non-field error keys is numbers

     * @return array
     */
    public function getAllErrors()
    {
        $all = array();
        foreach ($this->getFieldErrors() as $name => $text) {
            $all[$name] = $text;
        }
        foreach ($this->getCommonErrors() as $key => $text) {
            $all["0$key"] = $text;
        }
        return $all;
    }

    /**
     * Write all errors to client
     *
     * @return void
     */
    public function writeAllErrors($http_status = null)
    {
        if (is_null($http_status)) {
            $http_status = self::DEFAULT_ERROR_HTTP_CODE;
        }
        $this->setStatus($http_status);
        return $this->write($this->getAllErrors());
    }

    /**
     * @var array common (non-field) errors
     */
    private $commonErrors = array();

    /**
     * @var array field errors
     */
    private $fieldErrors = array();
}
