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
    /**
     * @dataProvider data_generateContent
     * @param $cui
     */
    public function test_generateContent($cui)
    {
        $directory = TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR . 'Parsers' . DIRECTORY_SEPARATOR . 'CompanyPage' . DIRECTORY_SEPARATOR;
        $html = file_get_contents(
            $directory . 'page-'.$cui.'.html'
        );

        $parameters = require $directory . 'page-'.$cui.'.php';

        $scrapper = new CompanyPageScraper($cui);

        $crawler = new Crawler(null, 'http://www.mfinante.gov.ro/infocodfiscal.html?cod='.$cui);
        $crawler->addContent($html, 'text/html;charset=utf-8');

        $parser = new CompanyPageParser();
        $parser->setScraper($scrapper);
        $parser->setCrawler($crawler);

        self::assertEquals($parameters, $parser->getContent());
    }

    /**
     * @return []
     */
    public function data_generateContent()
    {
        return [
            ['32586219'],
            ['33728613'],
        ];
    }
}
