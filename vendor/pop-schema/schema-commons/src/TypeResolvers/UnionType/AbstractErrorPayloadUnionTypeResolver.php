<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\TypeResolvers\UnionType;

use PoPSchema\SchemaCommons\TypeResolvers\InterfaceType\ErrorPayloadInterfaceTypeResolver;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\AbstractUnionTypeResolver;
abstract class AbstractErrorPayloadUnionTypeResolver extends AbstractUnionTypeResolver
{
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\InterfaceType\ErrorPayloadInterfaceTypeResolver|null
     */
    private $errorPayloadInterfaceTypeResolver;
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\InterfaceType\ErrorPayloadInterfaceTypeResolver $errorPayloadInterfaceTypeResolver
     */
    public final function setErrorPayloadInterfaceTypeResolver($errorPayloadInterfaceTypeResolver) : void
    {
        $this->errorPayloadInterfaceTypeResolver = $errorPayloadInterfaceTypeResolver;
    }
    protected final function getErrorPayloadInterfaceTypeResolver() : ErrorPayloadInterfaceTypeResolver
    {
        /** @var ErrorPayloadInterfaceTypeResolver */
        return $this->errorPayloadInterfaceTypeResolver = $this->errorPayloadInterfaceTypeResolver ?? $this->instanceManager->getInstance(ErrorPayloadInterfaceTypeResolver::class);
    }
    /**
     * @return InterfaceTypeResolverInterface[]
     */
    public function getUnionTypeInterfaceTypeResolvers() : array
    {
        return [$this->getErrorPayloadInterfaceTypeResolver()];
    }
}
