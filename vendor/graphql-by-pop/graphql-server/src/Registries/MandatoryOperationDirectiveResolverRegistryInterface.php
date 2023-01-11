<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
interface MandatoryOperationDirectiveResolverRegistryInterface
{
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface $directiveResolver
     */
    public function addMandatoryOperationDirectiveResolver($directiveResolver) : void;
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryOperationDirectiveResolvers() : array;
}
