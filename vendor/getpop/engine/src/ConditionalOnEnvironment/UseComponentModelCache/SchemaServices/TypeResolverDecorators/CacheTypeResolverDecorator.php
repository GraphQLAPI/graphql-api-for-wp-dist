<?php

declare (strict_types=1);
namespace PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\LoadCacheDirectiveResolver;
use PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\SaveCacheDirectiveResolver;
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
        $loadCacheDirective = $fieldQueryInterpreter->getDirective(\PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\LoadCacheDirectiveResolver::getDirectiveName());
        return [\PoP\Engine\ConditionalOnEnvironment\UseComponentModelCache\SchemaServices\DirectiveResolvers\SaveCacheDirectiveResolver::getDirectiveName() => [$loadCacheDirective]];
    }
}
