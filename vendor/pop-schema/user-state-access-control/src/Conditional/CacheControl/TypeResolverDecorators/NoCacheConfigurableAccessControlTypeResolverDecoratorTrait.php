<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\Conditional\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
trait NoCacheConfigurableAccessControlTypeResolverDecoratorTrait
{
    protected function getMandatoryDirectives($entryValue = null) : array
    {
        return [\PoP\CacheControl\Helpers\CacheControlHelper::getNoCacheDirective()];
    }
}
