<?php

namespace ByTIC\MFinante;

use ByTIC\MFinante\Parsers\BalanceSheetPage as BalanceSheetPageParser;
use ByTIC\MFinante\Parsers\CompanyPage as CompanyPageParser;
use ByTIC\MFinante\Scrapers\BalanceSheetPage as BalanceSheetPageScraper;
use ByTIC\MFinante\Scrapers\CompanyPage as CompanyPageScraper;

/**
 * Class MFinante
 * @package ByTIC\Mfinante
 */
class MFinante
{
    /**
     * @param $cif
     * @param array $params
     * @return CompanyPageParser
     */
    public static function cif($cif, $params = [])
    {
        $scraper = new CompanyPageScraper($cif);
        $scraper->setParams($params);
        return static::executeScraper($scraper);
    }

    /**
     * @param int $cif
     * @param int $year
     * @param array $params
     * @return BalanceSheetPageParser
     */
    public static function balanceSheet($cif, $year, $params = [])
    {
        $scraper = new BalanceSheetPageScraper($cif, $year);
        $scraper->setParams($params);
        return static::executeScraper($scraper);
    }

    /**
     * @param $scraper
     * @return mixed
     */
    protected static function executeScraper($scraper)
    {
        return $scraper->execute();
    }

}
