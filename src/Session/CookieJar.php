<?php

namespace ByTIC\MFinante\Session;

/**
 * Class CookieJar
 * @package ByTIC\MFinante\Session
 */
class CookieJar extends \Symfony\Component\BrowserKit\CookieJar
{
    /**
     * @return static
     */
    public static function instance()
    {
        $instance = new static();
        $cookies = CookieJarStorage::get();
        foreach ($cookies as $cookie) {
            $instance->set($cookie);
        }
        return $instance;
    }

    public function __destruct()
    {
        CookieJarStorage::save($this->all());
    }
}
