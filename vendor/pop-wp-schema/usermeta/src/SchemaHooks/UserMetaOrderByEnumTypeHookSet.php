<?php

declare(strict_types=1);

namespace PoPWPSchema\UserMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractMetaOrderByEnumTypeHookSet;

class UserMetaOrderByEnumTypeHookSet extends AbstractMetaOrderByEnumTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    protected function isEnumTypeResolver($enumTypeResolver): bool
    {
        return $enumTypeResolver instanceof UserOrderByEnumTypeResolver;
    }
}
