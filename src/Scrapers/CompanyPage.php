<?php

namespace ByTIC\MFinante\Scrapers;

use ByTIC\MFinante\Exception\InvalidCifException;
use ByTIC\MFinante\Helper;
use ByTIC\MFinante\Parsers\CompanyPage as Parser;

/**
 * Class CompanyPage
 * @package ByTIC\MFinante\Scrapers
 *
 * @method Parser execute()
 */
class CompanyPage extends AbstractScraper
{
    /**
     * @var int
     */
    protected $cif;

    /**
     * CompanyPage constructor.
     * @param int $cif
     */
    public function __construct($cif)
    {
        $this->setCif($cif);
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
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        if (!Helper::validateCif($this->getCif())) {
            throw new InvalidCifException();
        }

        /** IMPORTANT - the delay is necessary to make sure the javascript is all loaded */
        $this->getClient()->getClient()->setConfig('request_delay', 12);

        $crawler = $this->getClient()->request(
            'POST',
            static::$domain . '/infocodfiscal.html',
            [
                'pagina' => 'domenii',
                'cod' => $this->getCif(),
                'captcha' => 'null',
                'B1' => 'VIZUALIZARE'
            ]
        );
        return $crawler;
    }
}
