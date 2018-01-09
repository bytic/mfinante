<?php

namespace ByTIC\MFinante\LabelMaps\BalanceSheet;

/**
 * Class LabelMap
 * @package ByTIC\MFinante\LabelMaps\BalanceSheet
 */
class LabelMap
{
    /**
     * @var array
     */
    protected static $labels = null;

    /**
     * @param $label
     *
     * @return Label
     */
    public static function lookup($label)
    {
        $labels = static::getLabels();

        return isset($labels[$label]) ? $labels[$label] : new Label();
    }

    /**
     * @return array
     */
    protected static function getLabels()
    {
        if (self::$labels === null) {
            self::$labels = self::generateLabels();
        }

        return self::$labels;
    }

    /**
     * @return array
     */
    protected static function generateLabels()
    {
        $labels = self::importDataFile('chapter');
        $labels += self::importDataFile('section');
        $labels += self::importDataFile('indicator');

        return $labels;
    }

    /**
     * @param $type
     *
     * @return array
     */
    protected static function importDataFile($type)
    {
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $type . 's.php';
        $labels = require $filePath;
        $return = [];
        foreach ($labels as $label => $field) {
            $return[$label] = self::newLabel($type, $label, $field);
        }

        return $return;
    }

    /**
     * @param $type
     * @param $label
     * @param $value
     *
     * @return Label
     */
    protected static function newLabel($type, $label, $value)
    {
        return new Label($type, $label, $value);
    }
}
