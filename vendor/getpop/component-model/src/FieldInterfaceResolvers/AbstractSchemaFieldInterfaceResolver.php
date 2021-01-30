<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\ComponentModel\FieldInterfaceResolvers\SelfFieldInterfaceSchemaDefinitionResolverTrait;
abstract class AbstractSchemaFieldInterfaceResolver extends \PoP\ComponentModel\FieldInterfaceResolvers\AbstractFieldInterfaceResolver
{
    use SelfFieldInterfaceSchemaDefinitionResolverTrait;
}
