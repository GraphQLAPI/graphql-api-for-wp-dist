<?php

declare (strict_types=1);
namespace PoPSchema\UserStateAccessControl\ConditionalOnComponent\CacheControl\TypeResolverDecorators;

use PoP\CacheControl\Helpers\CacheControlHelper;
trait NoCacheConfigurableAccessControlTypeResolverDecoratorTrait
{
    /**
     * @param mixed $entryValue
     */
    protected function getMandatoryDirectives($entryValue = null) : array
    {
        return [CacheControlHelper::getNoCacheDirective()];
    }
}
