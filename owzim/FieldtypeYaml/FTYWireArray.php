<?php

namespace owzim\FieldtypeYaml;

if (PROCESSWIRE >= 300) {
    class FTYWireArray extends \ProcessWire\WireArray {}
} else {
    class FTYWireArray extends \WireArray {}
}
