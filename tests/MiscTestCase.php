<?php namespace FieldtypeYaml;

use \owzim\FieldtypeYaml\FTY;
use \owzim\FieldtypeYaml\FTYArray;
use \owzim\FieldtypeYaml\FTYData;

class MiscTestCase extends TestCaseBase
{
    public function dataProviderParseYaml()
    {
        return [
            // $value, $parseAs, $toStringString, $assertedToStringString, $assertedClass
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
            // $object, $toStringString, $assertedString
            [new FTYArray(), 'foo', 'foo (0)'],
            [new FTYData(), 'foo', 'foo'],
        ];
    }

    public function testToString($object, $toStringString, $assertedString)
    {
        $object->toStringString = $toStringString;
        $this->assertSame("$object", $assertedString);
    }

    public function testMap()
    {
        $value = $this->src('map.yaml');
        $toStringString = 'qux';
        $baseAssert = [
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'qux',
        ];

        $this->title('map to assoc');
            $result = FTY::parseYAML($value, FTY::PARSE_AS_ASSOC, $toStringString);
            $assert = $baseAssert;
            $this->assertArray($result);
            $this->assertEqual($result, $assert);

        $this->title('map to object');
            $result = FTY::parseYAML($value, FTY::PARSE_AS_OBJECT, $toStringString);
            $assert = (object) $baseAssert;
            $this->assertObject($result);
            $this->assertEqual($result, $assert);

        $this->title('map to wirearray/wiredata');
            $result = FTY::parseYAML($value, FTY::PARSE_AS_WIRE_DATA, $toStringString);
            $this->assertInstanceOf($result, '\owzim\FieldtypeYaml\FTYData');
            $this->assertSame("$result", 'qux');

            foreach ($baseAssert as $k => $v) {
                $this->assertSame($result->{$k}, $v);
                $this->assertSame($result->get("zoo|$k"), $v);
            }

        $this->title('map to wirearray');
            $result = FTY::parseYAML($value, FTY::PARSE_AS_WIRE_ARRAY, $toStringString);
            $this->assertInstanceOf($result, '\owzim\FieldtypeYaml\FTYArray');
            $this->assertSame("$result", 'qux (1)');

            foreach ($baseAssert as $k => $v) {
                $this->assertSame($result->first()->{$k}, $v);
                $this->assertSame($result->first()->get("zoo|$k"), $v);
                $this->assertSame($result->get("$k=$v"), $result->first());
            }
    }

    public function testList()
    {
        $value = $this->src('list.yaml');
        $toStringString = 'qux';
        $baseAssert = [
            [
                'foo' => 'bar',
                'bar' => 'baz',
                'baz' => 'qux',
            ],
            [
                'subspace' => 'wave',
                'riker' => 'data',
                'biofilters' => 'imminent',
            ]
        ];

        $this->title('list to assoc');
            $result = FTY::parseYAML($value, FTY::PARSE_AS_ASSOC, $toStringString);
            $assert = $baseAssert;
            $this->assertArray($result);
            $this->assertEqual($result, $assert);

        $this->title('list to object');
            $result = FTY::parseYAML($value, FTY::PARSE_AS_OBJECT, $toStringString);
            $assert = [
                (object) $baseAssert[0],
                (object) $baseAssert[1],
            ];
            $this->assertArray($result);
            $this->assertEqual($result, $assert);


        $this->title('list to wirearray');

            foreach ([
                FTY::parseYAML($value, FTY::PARSE_AS_WIRE_DATA, $toStringString),
                FTY::parseYAML($value, FTY::PARSE_AS_WIRE_ARRAY, $toStringString),
                ] as $result) {

                $this->assertInstanceOf($result, '\owzim\FieldtypeYaml\FTYArray');
                $this->assertSame("$result", 'qux (2)');

                foreach ($baseAssert[0] as $k => $v) {
                    $this->assertSame($result->eq(0)->{$k}, $v);
                    $this->assertSame($result->eq(0)->get("zoo|$k"), $v);
                    $this->assertSame($result->get("$k=$v"), $result->eq(0));
                }

                foreach ($baseAssert[1] as $k => $v) {
                    $this->assertSame($result->eq(1)->{$k}, $v);
                    $this->assertSame($result->eq(1)->get("zoo|$k"), $v);
                    $this->assertSame($result->get("$k=$v"), $result->eq(1));
                }
            }
    }
}
