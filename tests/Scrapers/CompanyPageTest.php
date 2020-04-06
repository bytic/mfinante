<?php

namespace ByTIC\MFinante\Tests\Scrapers;

use ByTIC\MFinante\Scrapers\CompanyPage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyPageTest
 * @package ByTIC\MFinante\Tests\Scrapers
 */
class CompanyPageTest extends TestCase
{
    public function testGetCrawler()
    {
        $scraper = new CompanyPage('32586219');
        $crawler = $scraper->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame('https://www.mfinante.gov.ro/infocodfiscal.html?cod=32586219', $crawler->getUri());

        static::assertStringContainsString('32586219', $crawler->html());
    }
}
