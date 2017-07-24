<?php

namespace ByTIC\MFinante;

/**
 * Class Helper
 * @package ByTIC\MFinante
 */
class Helper
{
    /**
     * @param $cif
     * @return bool
     */
    public static function validateCif($cif)
    {
        if (!is_numeric($cif)) {
            return false;
        }
        if (strlen($cif) > 10) {
            return false;
        }

        $controlDigit = substr($cif, -1);
        $cif = substr($cif, 0, -1);
        while (strlen($cif) != 9) {
            $cif = '0' . $cif;
        }

        $sum = $cif[0] * 7
            + $cif[1] * 5
            + $cif[2] * 3
            + $cif[3] * 2
            + $cif[4] * 1
            + $cif[5] * 7
            + $cif[6] * 5
            + $cif[7] * 3
            + $cif[8] * 2;

        $sum = $sum * 10;
        $rest = fmod($sum, 11);
        if ($rest == 10) {
            $rest = 0;
        }

        if ($rest == $controlDigit) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Convert a string to camelCase. Strings already in camelCase will not be harmed.
     *
     * @param  string $str The input string
     * @return string camelCased output string
     */
    public static function camelCase($str)
    {
        $str = self::convertToLowercase($str);
        return preg_replace_callback(
            '/_([a-z])/',
            function ($match) {
                return strtoupper($match[1]);
            },
            $str
        );
    }

    /**
     * Convert strings with underscores to be all lowercase before camelCase is preformed.
     *
     * @param  string $str The input string
     * @return string The output string
     */
    protected static function convertToLowercase($str)
    {
        $explodedStr = explode('_', $str);

        if (count($explodedStr) > 1) {
            foreach ($explodedStr as $value) {
                $lowerCasedStr[] = strtolower($value);
            }
            $str = implode('_', $lowerCasedStr);
        }

        return $str;
    }
}
