<?php

namespace owzim\FieldtypeYaml;

class FTYData extends FTYWireData
{

    public $toStringString = '';

    public function __toString()
    {
        return $this->toStringString;
    }
}
