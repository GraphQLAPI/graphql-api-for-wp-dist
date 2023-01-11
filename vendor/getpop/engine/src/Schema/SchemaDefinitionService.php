<?php

declare (strict_types=1);
namespace PoP\Engine\Schema;

use PoP\Root\Services\BasicServiceTrait;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
class SchemaDefinitionService implements \PoP\Engine\Schema\SchemaDefinitionServiceInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver|null
     */
    private $rootObjectTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver|null
     */
    private $anyBuiltInScalarScalarTypeResolver;
    /**
     * @param \PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver $rootObjectTypeResolver
     */
    public final function setRootObjectTypeResolver($rootObjectTypeResolver) : void
    {
        $this->rootObjectTypeResolver = $rootObjectTypeResolver;
    }
    protected final function getRootObjectTypeResolver() : RootObjectTypeResolver
    {
        /** @var RootObjectTypeResolver */
        return $this->rootObjectTypeResolver = $this->rootObjectTypeResolver ?? $this->instanceManager->getInstance(RootObjectTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver
     */
    public final function setAnyBuiltInScalarScalarTypeResolver($anyBuiltInScalarScalarTypeResolver) : void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    protected final function getAnyBuiltInScalarScalarTypeResolver() : AnyBuiltInScalarScalarTypeResolver
    {
        /** @var AnyBuiltInScalarScalarTypeResolver */
        return $this->anyBuiltInScalarScalarTypeResolver = $this->anyBuiltInScalarScalarTypeResolver ?? $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    /**
     * The `AnyBuiltInScalar` type is a wildcard type,
     * representing any of GraphQL's built-in types
     * (String, Int, Boolean, Float or ID)
     */
    public function getDefaultConcreteTypeResolver() : ConcreteTypeResolverInterface
    {
        return $this->getAnyBuiltInScalarScalarTypeResolver();
    }
    /**
     * The `AnyBuiltInScalar` type is a wildcard type,
     * representing any of GraphQL's built-in types
     * (String, Int, Boolean, Float or ID)
     */
    public function getDefaultInputTypeResolver() : InputTypeResolverInterface
    {
        return $this->getAnyBuiltInScalarScalarTypeResolver();
    }
    public function getSchemaRootObjectTypeResolver() : \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface
    {
        return $this->getRootObjectTypeResolver();
    }
}
