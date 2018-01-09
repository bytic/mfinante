<?php

namespace ByTIC\MFinante\Parsers\BalanceSheet;

use ByTIC\MFinante\LabelMaps\BalanceSheet\Label;
use ByTIC\MFinante\LabelMaps\BalanceSheet\LabelMap;
use ByTIC\MFinante\Parsers\BalanceSheet\Rows\AbstractRow;
use ByTIC\MFinante\Parsers\BalanceSheet\Rows\Chapter;
use ByTIC\MFinante\Parsers\BalanceSheet\Rows\Indicator;
use ByTIC\MFinante\Parsers\BalanceSheet\Rows\Section;
use DOMComment;
use DOMElement;

/**
 * Class RowClassifier
 * @package ByTIC\MFinante\Parsers\BalanceSheet
 */
class RowClassifier
{
    /**
     * @var DOMElement
     */
    protected $domElement;

    protected $year;

    /**
     * @var Chapter
     */
    protected $chapter = null;

    /**
     * @var Section
     */
    protected $section = null;

    /**
     * @var Indicator
     */
    protected $indicator = null;

    /**
     * @param DOMElement $domElement
     *
     * @return bool|AbstractRow|Chapter|Indicator|Section
     */
    public function determine(DOMElement $domElement)
    {
        $this->domElement = $domElement;

        if (!$this->isValidTableRow($domElement)) {
            return false;
        }

        $label = $this->prepareLabel(
            $this->getDomElement()->childNodes->item(0)->nodeValue
        );

        $lookUp = LabelMap::lookup($label);

        if ($lookUp->getType()) {
            return $this->generateRow($lookUp);
        }

        return false;
    }

    /**
     * @param DOMElement $row
     *
     * @return boolean
     */
    protected function isValidTableRow($row)
    {
        if ($row->childNodes[0] instanceof DOMComment) {
            return false;
        }

        if ($row->childNodes->length != 4) {
            return false;
        }

        return true;
    }

    /**
     * @param string $label
     *
     * @return mixed
     */
    protected function prepareLabel($label)
    {
        $label = str_replace(':', '', $label);
        $label = str_replace('(*)', '', $label);
        $label = str_replace('(**)', '', $label);
        $label = str_replace("\n", ' ', $label);
        $label = str_replace("\t", ' ', $label);
        $label = preg_replace('/\s+/', ' ', $label);
        $label = trim($label);

        return $label;
    }

    /**
     * @return DOMElement
     */
    public function getDomElement(): DOMElement
    {
        return $this->domElement;
    }

    /**
     * @param Label $label
     * @return AbstractRow
     */
    protected function generateRow($label)
    {
        $method = 'generate' . ucfirst($label->getType());
        $row = $this->$method($label);
        $this->{$label->getType()} = $row;
        return $row;
    }

    /**
     * @param Label $label
     * @return Chapter
     */
    protected function generateChapter($label)
    {
        $chapter = new Chapter();
        $chapter->setLabel($label->getLabel());
        $chapter->setName($label->getName());
        return $chapter;
    }

    /**
     * @param Label $label
     * @return Section
     */
    protected function generateSection($label)
    {
        $section = new Section();
        $section->setLabel($label->getLabel());
        $section->setName($label->getName());
        return $section;
    }

    /**
     * @param Label $label
     * @return Indicator
     */
    protected function generateIndicator($label)
    {
        $indicator = new Indicator();
        $indicator->setLabel(
            sprintf(
                $label->getLabel(),
                $this->chapter ? $this->chapter->getName() : '==',
                $this->section ? $this->section->getName() : '++'
            )
        );
        $indicator->setName(
            sprintf(
                $label->getName(),
                $this->chapter ? $this->chapter->getName() : '==',
                $this->section ? $this->section->getName() : '++'
            )
        );

        $value = $this->prepareValue(
            $this->getDomElement()->childNodes->item(2)->nodeValue
        );
        $indicator->setValue($value);
        return $indicator;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function prepareValue($value)
    {
        $value = str_replace("\n", ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

        return $value;
    }

    protected function getParsedLabel()
    {
        $this->getDomElement()->childNodes->item(0)->nodeValue;
    }
}
