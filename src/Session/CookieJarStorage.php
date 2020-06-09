<?php

namespace ByTIC\MFinante\Session;

use Symfony\Component\BrowserKit\Cookie;

/**
 * Class CookieJarStorage
 * @package ByTIC\MFinante\Session
 */
class CookieJarStorage
{
    /**
     * @param Cookie[] $cookies
     */
    public static function save($cookies)
    {
        $data = serialize($cookies);
        file_put_contents(static::path(), $data);
    }

    /**
     * @return Cookie[]
     */
    public static function get()
    {
        $path = static::path();
        if (!file_exists($path)) {
            return [];
        }
        $content = file_get_contents($path);
        $data = unserialize($content);
        return $data;
    }

    public static function clear()
    {
    }

    /**
     * @return string
     */
    public static function path()
    {
        return dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'cookies.serialized';
    }
}