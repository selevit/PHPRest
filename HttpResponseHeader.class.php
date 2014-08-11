<?php

class HttpResponseHeader
{
    /**
     * Конструктор HttpResponseHeader
     * @param string $key ключ
     * @param string $val значение
     */
    public function __construct($key, $val)
    {
        $this->setKey($key);
        $this->setVal($val);
    }

    /**
     * Установить ключ заголовка
     * @param string $value ключ заголовка
     */
    public function setKey($value)
    {
        if (!is_string($value) || empty($value))
            throw new InvalidArgumentException(
                "header key must be a non-empty string");
        $this->key = $value;
    }

    /**
     * Установить значение заголовка
     * @param string $value значение заголовка
     */
    public function setVal($value)
    {
        if (!is_string($value) || empty($value))
            throw new InvalidArgumentException(
                "header value must be a non-empty string");
        $this->val = $value;
    }

    public function toString()
    {
        return $this->key . ":" . $this->val;
    }

    /**
     * Записать заголовок в ответ
     */
    public function write()
    {
        header($this->toString());
    }

    private $key;
    private $val;
}
