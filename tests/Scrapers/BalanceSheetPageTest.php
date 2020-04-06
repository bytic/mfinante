<?php

namespace ByTIC\MFinante\Tests\Scrapers;

use ByTIC\MFinante\Scrapers\BalanceSheetPage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CompanyPageTest
 * @package ByTIC\MFinante\Tests\Scrapers
 */
class BalanceSheetPageTest extends TestCase
{
    public function testGetCrawler()
    {
        $scraper = new BalanceSheetPage('32586219', 2014);
        $crawler = $scraper->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'https://www.mfinante.gov.ro/infocodfiscal.html?an=WEB_ONG_AN2014&cod=32586219'
            . '&captcha=null&method.bilant=VIZUALIZARE',
            $crawler->getUri()
        );

        static::assertContains('32586219', $crawler->html());
        static::assertContains('ASOCIATIA PEOPLE FOR SPORT', $crawler->html());
        static::assertContains('financiare anuale', $crawler->html());
        static::assertContains('de personal privind activitatile economice sau financiare', $crawler->html());
    }
}
