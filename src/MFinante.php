<?php

namespace ByTIC\MFinante;

use ByTIC\MFinante\Parsers\AbstractParser;
use ByTIC\MFinante\Scrapers\CompanyPage;

/**
 * Class MFinante
 * @package ByTIC\Mfinante
 */
class MFinante
{
    /**
     * @param $cif
     * @return AbstractParser
     */
    public static function cif(int $cif)
    {
        return (new CompanyPage($cif))->execute();
    }
}
