<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
interface SchemaDefinitionServiceInterface
{
    public function getInterfaceSchemaKey(\PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface $interfaceResolver) : string;
    public function getTypeSchemaKey(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : string;
}
