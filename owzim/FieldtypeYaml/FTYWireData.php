<?php

namespace owzim\FieldtypeYaml;

if (PROCESSWIRE >= 300) {
    class FTYWireData extends \ProcessWire\WireData {}
} else {
    class FTYWireData extends \WireData {}
}
