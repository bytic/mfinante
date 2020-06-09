<?php

namespace ByTIC\MFinante\Scrapers;

use ByTIC\MFinante\Parsers\AbstractParser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractScraper
 * @package ByTIC\MFinante\Scrapers
 */
abstract class AbstractScraper
{
    use Traits\HasClient;
    use Traits\HasParams;

    protected static $domain = 'https://www.mfinante.gov.ro';

    /**
     * @var Crawler
     */
    protected $crawler = null;

    /**
     * @return AbstractParser
     */
    public function execute()
    {
        $crawler = $this->getCrawler();
        $parser = $this->getNewParser();
        $parser->setCrawler($crawler);

        return $parser;
    }

    /**
     * @return Crawler
     */
    public function getCrawler()
    {
        if (!$this->crawler) {
            $this->initCrawler();
        }

        return $this->crawler;
    }

    protected function initCrawler()
    {
        $this->crawler = $this->generateCrawler();
    }

    /**
     * @return Crawler
     */
    abstract protected function generateCrawler();

    /**
     * @return AbstractParser
     */
    protected function getNewParser()
    {
        $class = $this->getParserName();
        /** @var AbstractParser $parser */
        $parser = new $class();
        $parser->setScraper($this);
        return $parser;
    }

    /**
     * @return string
     */
    protected function getParserName()
    {
        $fullClassName = get_class($this);
        $partsClassName = explode('\\', $fullClassName);
        $classFirstName = array_pop($partsClassName);
        $classNamespacePath = implode('\\', $partsClassName);
        $classNamespacePath = str_replace('\Scrapers', '', $classNamespacePath);

        return $classNamespacePath.'\Parsers\\'.$classFirstName;
    }
}
