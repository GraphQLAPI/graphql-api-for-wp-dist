<?php

declare (strict_types=1);
namespace PoP\CacheControl\RelationalTypeResolverDecorators;

use PoP\CacheControl\DirectiveResolvers\CacheControlFieldDirectiveResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
trait ConfigurableCacheControlRelationalTypeResolverDecoratorTrait
{
    /**
     * @var array<string|int,Directive>
     */
    protected $cacheControlDirectives = [];
    protected abstract function getCacheControlFieldDirectiveResolver() : CacheControlFieldDirectiveResolver;
    /**
     * By default, only the admin can see the roles from the users
     *
     * @return Directive[]
     * @param mixed $entryValue
     */
    protected function getMandatoryDirectives($entryValue = null) : array
    {
        $maxAge = $entryValue;
        return [$this->getCacheControlDirective($maxAge)];
    }
    /**
     * @param string|int $maxAge
     */
    protected function getCacheControlDirective($maxAge) : Directive
    {
        if (!isset($this->cacheControlDirectives[$maxAge])) {
            $nonSpecificLocation = ASTNodesFactory::getNonSpecificLocation();
            $this->cacheControlDirectives[$maxAge] = new Directive($this->getCacheControlFieldDirectiveResolver()->getDirectiveName(), [new Argument('maxAge', new Literal($maxAge, $nonSpecificLocation), $nonSpecificLocation)], $nonSpecificLocation);
        }
        return $this->cacheControlDirectives[$maxAge];
    }
}
