<?php

namespace ByTIC\MFinante\Tests\Parsers;

use ByTIC\MFinante\Exception\InvalidArgumentException;
use ByTIC\MFinante\Parsers\BalanceSheetPage as BalanceSheetParser;
use ByTIC\MFinante\Scrapers\BalanceSheetPage as BalanceSheetScraper;
use ByTIC\MFinante\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BalanceSheetTest
 * @package ByTIC\MFinante\Tests\Parsers
 */
class BalanceSheetTest extends AbstractTest
{
    public function testGenerateContentOng()
    {
        $parameters = $this->getBalanceSheetParameters('32586219', 'WEB_ONG_AN2014');
        $parser     = $this->generateParser('32586219', 'WEB_ONG_AN2014');

        self::assertEquals($parameters, $parser->getContent());
    }

    public function testGenerateContentNoBalance()
    {
        $parser = $this->generateParser('6453132', 'WEB_ONG_AN2011');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No balance sheet for selected year');

        $parser->getContent();
    }

    /**
     * @param $cif
     * @param $year
     *
     * @return BalanceSheetParser
     */
    protected function generateParser($cif, $year)
    {
        $yearInteger = substr($year, -4);

        $parser = new BalanceSheetParser();
        $parser->setScraper(new BalanceSheetScraper($cif, $yearInteger));
        $parser->setCrawler($this->generateCrawler($cif, $year));

        return $parser;
    }

    /**
     * @param string $cif
     * @param string $year
     *
     * @return Crawler
     */
    protected function generateCrawler($cif, $year)
    {
        $html = $this->getBalanceSheetHtml($cif, $year);

        $crawler = new Crawler(
            null,
            'http://www.mfinante.gov.ro/infocodfiscal.html?an=' . $year . '&cod=' . $year . '&captcha=null'
            . '&method.bilant=VIZUALIZARE'
        );
        $crawler->addContent($html, 'text/html;charset=utf-8');

        return $crawler;
    }

    /**
     * @param $cui
     * @param $year
     *
     * @return bool|string
     */
    protected function getBalanceSheetHtml($cui, $year)
    {
        $path = DIRECTORY_SEPARATOR . 'Parsers'
                . DIRECTORY_SEPARATOR . 'balance_sheet-' . $cui . '-' . $year . '.html';

        return $this->getFixtureHtml($path);
    }

    /**
     * @param $cui
     * @param $year
     *
     * @return bool|string
     */
    protected function getBalanceSheetParameters($cui, $year)
    {
        $path = DIRECTORY_SEPARATOR . 'Parsers'
                . DIRECTORY_SEPARATOR . 'balance_sheet-' . $cui . '-' . $year . '.php';

        return $this->getFixtureParameters($path);
    }
}
