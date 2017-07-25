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
     * @return CompanyPageParser
     */
    public static function cif($cif)
    {
        return (new CompanyPageScraper($cif))->execute();
    }

    /**
     * @param int $cif
     * @param int $year
     * @return BalanceSheetPageParser
     */
    public static function balanceSheet($cif, $year)
    {
        return (new BalanceSheetPageScraper($cif, $year))->execute();
    }
}
