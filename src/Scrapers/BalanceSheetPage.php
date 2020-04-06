<?php

namespace ByTIC\MFinante\Scrapers;

use ByTIC\MFinante\Exception\InvalidArgumentException;
use ByTIC\MFinante\Exception\InvalidCifException;
use ByTIC\MFinante\Helper;
use ByTIC\MFinante\Parsers\BalanceSheetPage as Parser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BalanceSheetPage
 * @package ByTIC\MFinante\Scrapers
 *
 * @method Parser execute()
 */
class BalanceSheetPage extends AbstractScraper
{
    /**
     * @var int
     */
    protected $cif;

    /**
     * @var int
     */
    protected $year;

    /**
     * CompanyPage constructor.
     *
     * @param int $cif
     * @param int $year
     */
    public function __construct($cif, $year)
    {
        $this->setCif($cif);
        $this->setYear($year);
    }

    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        if ( ! Helper::validateCif($this->getCif())) {
            throw new InvalidCifException();
        }
        $year = intval($this->getYear());
        if ($year < 2000 or $year > date('Y')) {
            throw new InvalidArgumentException(
                'Year [' . $this->getYear() . '] is invalid'
            );
        }

        $params = [
            'an' => $this->getYearValue(),
            'cod' => $this->getCif(),
            'captcha' => 'null',
            'method.bilant' => 'VIZUALIZARE'
        ];

        $uri = static::$domain . '/infocodfiscal.html?' . http_build_query($params);

        /** IMPORTANT - the delay is necessary to make sure the javascript is all loaded */
        $this->getClient()->getClient()->setConfig('request_delay', 12);

        $crawler = $this->getClient()->request('GET', $uri);

        file_put_contents(
            __DIR__ . '/../../tests/fixtures/Parsers/balance_sheet-6453132-' . $this->getYearValue() . '.html',
            $crawler->html()
        );

        return $crawler;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function getYearValue()
    {
        $crawler = $this->getClient()->request(
            'GET',
            static::$domain . '/infocodfiscal.html?cod=' . $this->getCif()
        );

        return $this->parseYearValueFromCrawler($crawler);
    }

    /**
     * @param Crawler $crawler
     *
     * @return string
     * @throws InvalidArgumentException
     */
    protected function parseYearValueFromCrawler($crawler)
    {
        $anFieldValues = $crawler->filter('form[name="codfiscalForm"] select')->children()
                                 ->extract(['_text', 'value']);

        foreach ($anFieldValues as $anFieldValue) {
            if ($anFieldValue[0] == $this->getYear()) {
                return $anFieldValue[1];
            }
        }

        throw new InvalidArgumentException(
            'Year [' . $this->getYear() . '] is invalid'
        );
    }

    /**
     * @return int
     */
    public function getCif()
    {
        return $this->cif;
    }

    /**
     * @param int $cif
     */
    public function setCif($cif)
    {
        $this->cif = $cif;
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear(int $year)
    {
        $this->year = $year;
    }
}
