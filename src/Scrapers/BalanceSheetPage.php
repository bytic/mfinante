<?php

namespace ByTIC\MFinante\Scrapers;

use ByTIC\MFinante\Exception\InvalidArgumentException;
use ByTIC\MFinante\Exception\InvalidCifException;
use ByTIC\MFinante\Helper;
use ByTIC\MFinante\Parsers\BalanceSheetPage as Parser;

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
        if (!Helper::validateCif($this->getCif())) {
            throw new InvalidCifException();
        }
        $year = intval($this->getYear());
        if ($year < 2000 or $year > date('Y')) {
            throw new InvalidArgumentException(
                'Year [' . $this->getYear() . '] is invalid'
            );
        }

        $params = [
            'an'            => 'WEB_ONG_AN' . $year,
            'cod'           => $this->getCif(),
            'captcha'       => 'null',
            'method.bilant' => 'VIZUALIZARE'
        ];

        $crawler = $this->getClient()->request(
            'GET',
            'http://www.mfinante.ro/infocodfiscal.html?' . http_build_query($params)
        );

        return $crawler;
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
