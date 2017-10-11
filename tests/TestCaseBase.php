<?php namespace FieldtypeYaml;

class TestCaseBase extends \owzim\TestFest\TestFestSuite {

    public function init()
    {
        $this->modules->get('FieldtypeYaml');
    }

    public function src($file)
    {
        return file_get_contents(__DIR__ . "/src/$file");
    }

}
