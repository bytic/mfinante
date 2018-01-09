<?php

namespace ByTIC\MFinante\Parsers\BalanceSheet\Rows;

/**
 * Class Indicator
 * @package ByTIC\MFinante\Parsers\BalanceSheet\Rows
 */
class Indicator extends AbstractRow
{
    protected $section;

    /**
     * @return mixed
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * @param mixed $section
     */
    public function setSection($section)
    {
        $this->section = $section;
    }
}
