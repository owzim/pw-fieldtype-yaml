<?php

namespace owzim\FieldtypeYaml;

use \owzim\FieldtypeYaml\Vendor\Spyc\Spyc;

class FTY {

    const PARSE_AS_ASSOC = 0;
    const PARSE_AS_OBJECT = 1;
    const PARSE_AS_WIRE_DATA = 2;
    const PARSE_AS_WIRE_ARRAY = 3;
    const DEFAULT_PARSE_AS = 2;

    public static function isArray($array)
    {
        if (!is_array($array)) return false;
        $len = count($array);
        $iterator = 0;
        foreach ($array as $key => $value) {
            if (!is_numeric($key)) return false;
            if ((int) $key !== $iterator++) { return false; }
        }
        return true;
    }

    public static function isAssoc($array)
    {
        if (!is_array($array)) return false;
        return !self::isArray($array);
    }

    /**
     * merge two objects
     *
     * @param  object $obj1
     * @param  object $obj2
     * @return object the object resulting from merge
     */
    public static function objectMerge($obj1, $obj2)
    {
        return (object) array_merge((array) $obj1, (array) $obj2);
    }

    /**
     * convert an assoc array to an object recursively
     *
     * @param  array  $array
     * @return stdClass
     */
    public static function array2object(array $array)
    {
        $resultObj = new \stdClass;
        $resultArr = array();
        $hasIntKeys = false;
        $hasStrKeys = false;
        foreach ($array as $key => $value) {
            if (!$hasIntKeys) {
                $hasIntKeys = is_int($key);
            }
            if (!$hasStrKeys) {
                $hasStrKeys = is_string($key);
            }
            if ($hasIntKeys && $hasStrKeys) {
                $e = new \Exception(
                    'Current level has both int and str keys, thus it\'s impossible to keep arr or convert to obj'
                );
                $e->vars = array('level' => $array);
                throw $e;
            }
            if ($hasStrKeys) {
                $resultObj->{$key} = is_array($value) ? self::array2object($value) : $value;
            } else {
                $resultArr[$key] = is_array($value) ? self::array2object($value) : $value;
            }
        }
        return ($hasStrKeys) ? $resultObj : $resultArr;
    }

    /**
     * convert an assoc array to an object recursively
     *
     * @param  array  $array
     * @return stdClass
     */
    public static function array2wire(array $array)
    {
        $resultObj = new FTYData;
        $resultArr = array();
        $resultWireArr = new FTYArray;
        $hasIntKeys = false;
        $hasStrKeys = false;
        $wireArrAllowed = true;
        foreach ($array as $key => $value) {
            if (!$hasIntKeys) {
                $hasIntKeys = is_int($key);
            }
            if (!$hasStrKeys) {
                $hasStrKeys = is_string($key);
            }
            if ($hasIntKeys && $hasStrKeys) {
                $e = new \Exception(
                    'Current level has both int and str keys, thus it\'s impossible to keep arr or convert to obj'
                );
                $e->vars = array('level' => $array);
                throw $e;
            }
            if ($hasStrKeys) {
                $resultObj->{$key} = is_array($value) || is_object($value) ? self::array2wire($value) : $value;
            } else {
                $result = is_array($value) || is_object($value) ? self::array2wire($value) : $value;
                if ($wireArrAllowed && is_object($result)) {
                    $resultWireArr->add($result);
                } else {
                    $wireArrAllowed = false;
                    $resultArr[$key] = $result;
                }
            }
        }
        return ($hasStrKeys) ? $resultObj : ($wireArrAllowed ? $resultWireArr : $resultArr);
    }


    public static function parseYAML(
        $value,
        $parseAs = self::DEFAULT_PARSE_AS,
        $toStringString = '')
    {

        $value = trim($value);
        if (!$value) return self::getDefaultValue($value, $parseAs, $toStringString);

        switch (true) {
            case $parseAs === self::PARSE_AS_ASSOC:
                return Spyc::YAMLLoadString($value);
            case $parseAs === self::PARSE_AS_OBJECT:
                return self::array2object(Spyc::YAMLLoadString($value));
            case $parseAs === self::PARSE_AS_WIRE_DATA:
            case $parseAs === self::PARSE_AS_WIRE_ARRAY:
                $prewire = self::array2wire(Spyc::YAMLLoadString($value));

                if ($prewire instanceof FTYData && $parseAs === self::PARSE_AS_WIRE_ARRAY) {
                    $wire = new FTYArray();
                    $wire->add($prewire);
                } else {
                    $wire = $prewire;
                }

                if ($prewire instanceof FTYData || $prewire instanceof FTYArray) {
                    $wire->toStringString = $toStringString;
                }

                return $wire;
        }
    }

    public static function getDefaultValue($value, $parseAs, $toStringString)
    {
        switch (true) {
            default:
                return $value;
            case $parseAs === self::PARSE_AS_ASSOC:
                return array();
            case $parseAs === self::PARSE_AS_OBJECT:
                return new \stdClass();
            case $parseAs === self::PARSE_AS_WIRE_DATA:
                $wire = new FTYData();
                $wire->toStringString = $toStringString;
                return $wire;
            case $parseAs === self::PARSE_AS_WIRE_ARRAY:
                $wire = new FTYArray();
                $wire->toStringString = $toStringString;
                return $wire;
        }
    }
}
