<?php namespace FieldtypeYaml;

use \owzim\FieldtypeYaml\FTY;
use \owzim\FieldtypeYaml\FTYArray;
use \owzim\FieldtypeYaml\FTYData;

class MiscTestCase extends TestCaseBase
{
    public function dataProviderParseYaml()
    {
        return [
            // [$value, $parseAs, $toStringString, $assertedToStringString, $assertedClass],
            ['', FTY::PARSE_AS_ASSOC, 'foo', 'foo', '\Array'],
            ['', FTY::PARSE_AS_OBJECT, 'foo', 'foo', '\stdClass'],
            ['', FTY::PARSE_AS_WIRE_DATA, 'foo', 'foo', '\owzim\FieldtypeYaml\FTYData'],
            ['', FTY::PARSE_AS_WIRE_ARRAY, 'foo', 'foo (0)', '\owzim\FieldtypeYaml\FTYArray'],
        ];
    }

    public function testParseYaml($value, $parseAs, $toStringString, $assertedToStringString, $assertedClass)
    {
        $result = FTY::parseYAML($value, $parseAs, $toStringString);

        $class = get_class($result) === false ? (string) $result : get_class($result);
        $assertedClass = ltrim($assertedClass, '\\');

        $this->assertSame($class, $assertedClass);

        if (is_object($result) && method_exists($result, '__toString')) {
            $this->assertSame("$result", $assertedToStringString);
        }

    }

    public function dataProviderToString()
    {
        return [
            // [$object, $toStringString, $assertedString],
            [new FTYArray(), 'foo', 'foo (0)'],
            [new FTYData(), 'foo', 'foo'],
        ];
    }

    public function testToString($object, $toStringString, $assertedString)
    {
        $object->toStringString = $toStringString;
        $this->assertSame("$object", $assertedString);
    }
}
