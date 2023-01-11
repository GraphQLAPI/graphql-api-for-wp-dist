<?php

declare (strict_types=1);
namespace PoP\ComponentModel\QueryResolution;

interface InputObjectSubpropertyFieldDataAccessorInterface extends \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface
{
    public function getInputObjectSubpropertyName() : string;
}
