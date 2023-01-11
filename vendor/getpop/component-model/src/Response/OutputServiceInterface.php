<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Response;

use stdClass;
interface OutputServiceInterface
{
    /**
     * @param mixed[]|stdClass $value
     */
    public function jsonEncodeArrayOrStdClassValue($value) : string;
}
