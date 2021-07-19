<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
class DataloadingEngine implements \PoP\ComponentModel\Engine\DataloadingEngineInterface
{
    /**
     * @var DirectiveResolverInterface[]
     */
    protected $mandatoryDirectiveResolvers = [];
    public function addMandatoryDirectiveResolver(DirectiveResolverInterface $directiveResolver) : void
    {
        $this->mandatoryDirectiveResolvers[] = $directiveResolver;
    }
    /**
     * @return DirectiveResolverInterface[]
     */
    public function getMandatoryDirectiveResolvers() : array
    {
        return $this->mandatoryDirectiveResolvers;
    }
}
