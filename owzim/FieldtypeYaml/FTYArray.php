<?php

namespace owzim\FieldtypeYaml;

class FTYArray extends FTYWireArray
{

    public $toStringString = '';

    public function __toString()
    {
        $c = count($this);
        return "{$this->toStringString} ($c)";
    }

    public function isValidItem($item)
    {
        return $item instanceof FTYData || $item instanceof stdClass;
    }
}
