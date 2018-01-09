<?php

namespace ByTIC\MFinante\Parsers;

use ByTIC\MFinante\Exception\InvalidArgumentException;
use ByTIC\MFinante\Models\BalanceSheet;
use ByTIC\MFinante\Parsers\BalanceSheet\LabelMaps;
use ByTIC\MFinante\Parsers\BalanceSheet\Labels\IndicatorLabels;
use ByTIC\MFinante\Parsers\BalanceSheet\RowClassifier;
use ByTIC\MFinante\Parsers\BalanceSheet\Rows\Indicator;
use ByTIC\MFinante\Scrapers\BalanceSheetPage as Scraper;

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
        $rowClassifier = new RowClassifier();
        foreach ($rows as $row) {
            $rowResult = $rowClassifier->determine($row);
            if ($rowResult instanceof Indicator) {
                $return[$rowResult->getName()] = $rowResult->getValue();
            }
        }

        return $return;
    }
}
