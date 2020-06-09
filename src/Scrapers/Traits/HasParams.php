<?php

namespace ByTIC\MFinante\Scrapers\Traits;

/**
 * Trait HasParams
 * @package ByTIC\MFinante\Scrapers\Traits
 */
trait HasParams
{
    protected $params = [];

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed|null
     */
    public function getParam($name, $default = null)
    {
        if (!$this->hasParam($name)) {
            return $default;
        }
        return $this->params[$name];
    }

    /**
     * @param $name
     * @param $value
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($this->params[$name]);
    }
}
