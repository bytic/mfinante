<?php

namespace ByTIC\MFinante\Models;

use ByTIC\MFinante\Helper;

/**
 * Class AbstractModel
 * @package ByTIC\MFinante\Models
 */
abstract class AbstractModel
{

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        if (is_array($parameters)) {
            foreach ($parameters as $name => $value) {
                $method = 'set' . ucfirst(Helper::camelCase($name));
                if (method_exists($this, $method)) {
                    $this->$method($value);
                } elseif (property_exists($this, $name)) {
                    $this->{$name} = $value;
                }
            }
        }
    }
}
