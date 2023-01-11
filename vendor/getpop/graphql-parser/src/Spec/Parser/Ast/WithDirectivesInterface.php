<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithDirectivesInterface
{
    /**
     * @return Directive[]
     */
    public function getDirectives() : array;
    public function hasDirectives() : bool;
    /**
     * @internal Method used by the Engine for the Extended Spec's
     *           "MultiField Directives" feature. Don't call otherwise!
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    public function addDirective($directive) : void;
}
