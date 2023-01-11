<?php

declare (strict_types=1);
namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
abstract class AbstractSchemaDefinitionProvider implements \PoPAPI\API\ObjectModels\SchemaDefinition\SchemaDefinitionProviderInterface
{
    /**
     * @var array<string,TypeResolverInterface|FieldDirectiveResolverInterface> Key: class, Value: Accessed Type and Directive Resolver
     */
    protected $accessedTypeAndFieldDirectiveResolvers = [];
    public final function getAccessedTypeAndFieldDirectiveResolvers() : array
    {
        return \array_values($this->accessedTypeAndFieldDirectiveResolvers);
    }
}
