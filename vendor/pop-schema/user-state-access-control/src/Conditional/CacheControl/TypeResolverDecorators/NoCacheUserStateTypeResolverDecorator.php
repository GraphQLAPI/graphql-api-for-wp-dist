<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\CacheControl\Helpers\CacheControlHelper;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver;
use PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver;
class NoCacheUserStateTypeResolverDecorator extends \PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\ComponentModel\TypeResolvers\AbstractTypeResolver::class);
    }
    /**
     * If validating if the user is logged-in, then we can't cache the response
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getPrecedingMandatoryDirectivesForDirectives(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : array
    {
        $noCacheControlDirective = \PoP\CacheControl\Helpers\CacheControlHelper::getNoCacheDirective();
        return [\PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInDirectiveResolver::getDirectiveName() => [$noCacheControlDirective], \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserLoggedInForDirectivesDirectiveResolver::getDirectiveName() => [$noCacheControlDirective], \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInDirectiveResolver::getDirectiveName() => [$noCacheControlDirective], \PoPSchema\UserStateAccessControl\DirectiveResolvers\ValidateIsUserNotLoggedInForDirectivesDirectiveResolver::getDirectiveName() => [$noCacheControlDirective]];
    }
}
