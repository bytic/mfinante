<?php

namespace ByTIC\MFinante\Tests\Parsers;

use ByTIC\MFinante\Exception\InvalidArgumentException;
use ByTIC\MFinante\Parsers\BalanceSheetPage as BalanceSheetParser;
use ByTIC\MFinante\Scrapers\BalanceSheetPage as BalanceSheetScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BalanceSheetTest
 * @package ByTIC\MFinante\Tests\Parsers
 */
class BalanceSheetTest extends TestCase
{
    public function testGenerateContent()
    {
        $pathDir = TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR . 'Parsers' . DIRECTORY_SEPARATOR;
        $html    = file_get_contents(
            $pathDir . 'balance_sheet-32586219.html'
        );

        $parameters = require $pathDir . 'balance_sheet-32586219.php';

        $scrapper = new BalanceSheetScraper('32586219', 2014);

        $crawler = new Crawler(
            null,
            'http://www.mfinante.gov.ro/infocodfiscal.html?an=WEB_ONG_AN2014&cod=32586219&captcha=null'
            . '&method.bilant=VIZUALIZARE'
        );
        $crawler->addContent($html, 'text/html;charset=utf-8');

        $parser = new BalanceSheetParser();
        $parser->setScraper($scrapper);
        $parser->setCrawler($crawler);

        self::assertEquals($parameters, $parser->getContent());
    }

    public function testGenerateContentBadYear()
    {
        $pathDir = TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR . 'Parsers' . DIRECTORY_SEPARATOR;
        $html    = file_get_contents(
            $pathDir . 'balance_sheet-6453132-nobalance.html'
        );

        $scrapper = new BalanceSheetScraper('6453132', 2011);

        $crawler = new Crawler(
            null,
            'http://www.mfinante.gov.ro/infocodfiscal.html?an=WEB_ONG_AN2011&cod=6453132&captcha=null'
            . '&method.bilant=VIZUALIZARE'
        );
        $crawler->addContent($html, 'text/html;charset=utf-8');

        $parser = new BalanceSheetParser();
        $parser->setScraper($scrapper);
        $parser->setCrawler($crawler);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No balance sheet for selected year');

        $content = $parser->getContent();
    }
}
