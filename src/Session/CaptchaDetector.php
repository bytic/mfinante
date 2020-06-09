<?php

namespace ByTIC\MFinante\Session;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class CaptchaDetector
 * @package ByTIC\MFinante\Session
 */
class CaptchaDetector
{
    /**
     * @param Crawler $crawler
     * @return bool
     */
    public static function isCaptcha(Crawler $crawler)
    {
        return $crawler->filter('.fieldError')->count() > 0;
    }

    /**
     * @return array
     */
    public static function response()
    {
        $image = file_get_contents('output.jpeg');
        $image64 = base64_encode($image);
        return [
            'captcha' => true,
            'captcha-img' => $image64,
            'captcha-html' => '<img src="data:image/jpeg;base64, ' . $image64 . '" />',
        ];
    }

    /**
     * @return array
     */
    public static function phantomJsParams()
    {
        return [
            'outputFile' => 'output.jpeg',
            'viewportSize' => ['width' => 250, 'height' => 200],
            'captureDimensions' => ['width' => 250, 'height' => 200, 'top' => 400, 'left' => 200],
        ];
    }
}
