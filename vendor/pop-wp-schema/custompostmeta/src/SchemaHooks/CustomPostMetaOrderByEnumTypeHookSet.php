<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractMetaOrderByEnumTypeHookSet;

class CustomPostMetaOrderByEnumTypeHookSet extends AbstractMetaOrderByEnumTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    protected function isEnumTypeResolver($enumTypeResolver): bool
    {
        return $enumTypeResolver instanceof CustomPostOrderByEnumTypeResolver;
    }
}
