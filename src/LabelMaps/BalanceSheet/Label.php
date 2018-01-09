<?php

namespace ByTIC\MFinante\LabelMaps\BalanceSheet;

/**
 * Class Label
 * @package ByTIC\MFinante\LabelMaps\BalanceSheet
 */
class Label
{
    protected $label;
    protected $name;
    protected $type;

    /**
     * Label constructor.
     *
     * @param $label
     * @param $name
     * @param $type
     */
    public function __construct($type = null, $label = null, $name = null)
    {
        $this->label = $label;
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * @return null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }
}
