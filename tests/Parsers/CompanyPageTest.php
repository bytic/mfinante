<?php

namespace ByTIC\MFinante\Tests\Parsers;

use ByTIC\MFinante\Parsers\CompanyPage as CompanyPageParser;
use ByTIC\MFinante\Scrapers\CompanyPage as CompanyPageScraper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyPageTest
 * @package ByTIC\MFinante\Tests\Parsers
 */
class CompanyPageTest extends TestCase
{
    public function testGenerateContent()
    {
        $html = file_get_contents(
            TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR . 'Parsers' . DIRECTORY_SEPARATOR . 'page-32586219.html'
        );

        $parameters = require TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR . 'Parsers' . DIRECTORY_SEPARATOR . 'page-32586219.php';

        $scrapper = new CompanyPageScraper('32586219');

        $crawler = new Crawler(null, 'http://www.mfinante.gov.ro/infocodfiscal.html?cod=32586219');
        $crawler->addContent($html, 'text/html;charset=utf-8');

        $parser = new CompanyPageParser();
        $parser->setScraper($scrapper);
        $parser->setCrawler($crawler);

        self::assertEquals($parameters, $parser->getContent());
    }
}
