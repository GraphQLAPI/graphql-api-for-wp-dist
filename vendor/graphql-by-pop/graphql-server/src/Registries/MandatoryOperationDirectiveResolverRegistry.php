<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
class MandatoryOperationDirectiveResolverRegistry implements \GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface
{
    /**
     * @var FieldDirectiveResolverInterface[]
     */
    protected $mandatoryOperationDirectiveResolvers = [];
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface $directiveResolver
     */
    public function addMandatoryOperationDirectiveResolver($directiveResolver) : void
    {
        $this->mandatoryOperationDirectiveResolvers[] = $directiveResolver;
    }
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryOperationDirectiveResolvers() : array
    {
        return $this->mandatoryOperationDirectiveResolvers;
    }
}
