<?php

require_once dirname(__FILE__) . '/HttpResponseHeader.class.php';

class HttpResponseHeaderList
{
    /**
     * Добавить хедер
     * @param string $key ключ
     * @param string $val значение
     */
    public function add($key, $val)
    {
        if ($this->has($key))
            throw new LogicException("header '$key' already exists");
        $this->headers[] = new HttpResponseHeader($key, $val);
    }

    /**
     * Удалить хедер
     * @param string $key ключ
     */
    public function remove($key)
    {
        $index = $this->_index($key);
        if ($index === -1)
            throw new LogicException("header '$key' does not exists");
        unset($this->headers[$index]);
    }

    /**
     * Получить значение заголовка с ключом key
     * @param string $key ключ
     * @return HttpResponseHeader
     */
    public function get($key)
    {
        $index = $this->_index($key);
        if ($index === -1)
            throw new OutOfBoundsException("No key found: $key");
        return $this->headers[$index];
    }

    /**
     * Установить хедер (в случае отсутствия создает новый)
     * @param string $key ключ
     */
    public function set($key, $val)
    {
        $index = $this->_index($key);
        $header = new HttpResponseHeader($key, $val);
        if ($index === -1)
            $this->headers[] = $header;
        else
            $this->headers[$index] = $header;
    }

    /**
     * Проверить наличие заголовка с ключом
     * @param  string  $key ключ
     * @return boolean
     */
    public function has($key)
    {
        if ($this->_index($key) !== -1)
            return true;
        return false;
    }

    /**
     * Записать в ответ все заголовки
     */
    public function write()
    {
        foreach ($this->headers as $header) {
            $header->write();
        }
    }

    /**
     * Получить индекс значения в списке
     * @param  string $key ключ заголовка
     * @return integer индекс, либо -1 в случае отсутствия
     */
    private function _index($key)
    {
        for ($i = 0; $i < count($this->headers); $i++) {
            $header = $this->headers[$i];
            $current_key = $header->getKey();
            if (mb_strtolower($current_key) === mb_strtolower($key))
                return $i;
        }
        return -1;
    }

    private $headers = array();
}
