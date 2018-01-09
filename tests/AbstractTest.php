<?php

namespace ByTIC\MFinante\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractTest
 * @package ByTIC\MFinante\Tests
 */
abstract class AbstractTest extends TestCase
{

    /**
     * @param $path
     *
     * @return bool|string
     */
    protected function getFixtureHtml($path)
    {
        return file_get_contents($this->generateFixtureFullPath($path));
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function generateFixtureFullPath($path)
    {
        return TEST_FIXTURE_PATH . DIRECTORY_SEPARATOR . $path;
    }

    /**
     * @param $path
     *
     * @return bool|string
     */
    protected function getFixtureParameters($path)
    {
        /** @noinspection PhpIncludeInspection */
        return require $this->generateFixtureFullPath($path);
    }
}
