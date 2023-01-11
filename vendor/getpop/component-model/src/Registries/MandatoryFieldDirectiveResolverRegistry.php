<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Registries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
class MandatoryFieldDirectiveResolverRegistry implements \PoP\ComponentModel\Registries\MandatoryFieldDirectiveResolverRegistryInterface
{
    /**
     * @var FieldDirectiveResolverInterface[]
     */
    protected $mandatoryFieldDirectiveResolvers = [];
    /**
     * @param \PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface $directiveResolver
     */
    public function addMandatoryFieldDirectiveResolver($directiveResolver) : void
    {
        $this->mandatoryFieldDirectiveResolvers[] = $directiveResolver;
    }
    /**
     * @return FieldDirectiveResolverInterface[]
     */
    public function getMandatoryFieldDirectiveResolvers() : array
    {
        return $this->mandatoryFieldDirectiveResolvers;
    }
}
