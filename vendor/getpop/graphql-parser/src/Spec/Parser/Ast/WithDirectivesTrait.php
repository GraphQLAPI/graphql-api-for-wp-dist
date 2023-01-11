<?php

declare (strict_types=1);
namespace PoP\GraphQLParser\Spec\Parser\Ast;

trait WithDirectivesTrait
{
    /** @var Directive[] */
    protected $directives;
    public function hasDirectives() : bool
    {
        return \count($this->directives) > 0;
    }
    /**
     * @return Directive[]
     */
    public function getDirectives() : array
    {
        return $this->directives;
    }
    /**
     * @param Directive[] $directives
     */
    protected function setDirectives($directives) : void
    {
        $this->directives = $directives;
    }
    /**
     * @internal Method used by the Engine for the Extended Spec's
     *           "MultiField Directives" feature. Don't call otherwise!
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\Directive $directive
     */
    public function addDirective($directive) : void
    {
        $this->directives[] = $directive;
    }
}
