<?php

declare (strict_types=1);
namespace PoP\API\Enums;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Enums\AbstractEnum;
class SchemaFieldShapeEnum extends \PoP\ComponentModel\Enums\AbstractEnum
{
    public const NAME = 'SchemaOutputShape';
    protected function getEnumName() : string
    {
        return self::NAME;
    }
    public function getValues() : array
    {
        return [\PoP\API\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_FLAT, \PoP\API\Schema\SchemaDefinition::ARGVALUE_SCHEMA_SHAPE_NESTED];
    }
}
