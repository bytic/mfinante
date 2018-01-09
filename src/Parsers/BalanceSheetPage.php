<?php

namespace ByTIC\MFinante\Parsers;

use ByTIC\MFinante\Exception\InvalidArgumentException;
use ByTIC\MFinante\Models\BalanceSheet;
use ByTIC\MFinante\Parsers\BalanceSheet\LabelMaps;
use ByTIC\MFinante\Scrapers\BalanceSheetPage as Scraper;
use DOMComment;
use DOMElement;

/**
 * Class BalanceSheetPage
 * @package ByTIC\MFinante\Scrapers
 *
 * @method Scraper getScraper()
 */
class BalanceSheetPage extends AbstractParser
{

    /**
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return BalanceSheet::class;
    }

    /**
     * @return array
     */
    protected function generateContent()
    {
        $return = [];
        $return = array_merge($return, $this->parseTable());

        return $return;
    }

    /**
     * @inheritdoc
     */
    protected function doValidation()
    {
        $html = $this->getCrawler()->html();
        if (strpos($html, 'Nu exista bilant pentru anul selectat.') !== false) {
            throw new InvalidArgumentException('No balance sheet for selected year');
        }

        return parent::doValidation();
    }

    /**
     * @return array
     */
    protected function parseTable()
    {
        $table  = $this->getCrawler()->filter('#main > center > table > tbody')->first();
        $rows   = $table->children();
        $return = [];
        foreach ($rows as $row) {
            if ($this->isValidTableRow($row)) {
                $rowParsed = $this->parseTableRow($row);
                if ($rowParsed) {
                    $return[$rowParsed[0]] = $rowParsed[1];
                }
            }
        }

        return $return;
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
     * @param DOMElement $row
     *
     * @return array
     */
    protected function parseTableRow($row)
    {
        $label = $row->childNodes[0]->nodeValue;
        $label = str_replace(':', '', $label);
        $label = str_replace('(*)', '', $label);
        $label = str_replace('(**)', '', $label);
        $label = str_replace("\n", ' ', $label);
        $label = str_replace("\t", ' ', $label);
        $label = preg_replace('/\s+/', ' ', $label);
        $label = trim($label);

        $value = str_replace("\n", ' ', $row->childNodes[2]->nodeValue);
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

        $labels = (new LabelMaps($this->getScraper()->getYear()))->getLabels();

        $labelFind = isset($labels[$label]) ? $labels[$label] : null;
        if ($labelFind) {
            return [$labelFind, $value];
        }

        return [];
    }
}
