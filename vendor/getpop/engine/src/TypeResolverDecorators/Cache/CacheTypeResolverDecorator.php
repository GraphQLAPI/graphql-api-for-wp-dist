<?php

declare (strict_types=1);
namespace PoP\Engine\TypeResolverDecorators\Cache;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\DirectiveResolvers\Cache\LoadCacheDirectiveResolver;
use PoP\Engine\DirectiveResolvers\Cache\SaveCacheDirectiveResolver;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
class CacheTypeResolverDecorator extends \PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\ComponentModel\TypeResolvers\AbstractTypeResolver::class);
    }
    /**
     * Directives @loadCache and @saveCache (called @cache) always go together
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $fieldQueryInterpreter = \PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade::getInstance();
        $loadCacheDirective = $fieldQueryInterpreter->getDirective(\PoP\Engine\DirectiveResolvers\Cache\LoadCacheDirectiveResolver::getDirectiveName());
        return [\PoP\Engine\DirectiveResolvers\Cache\SaveCacheDirectiveResolver::getDirectiveName() => [$loadCacheDirective]];
    }
}
