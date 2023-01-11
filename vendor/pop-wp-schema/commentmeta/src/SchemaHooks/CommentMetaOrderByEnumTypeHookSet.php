<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractMetaOrderByEnumTypeHookSet;

class CommentMetaOrderByEnumTypeHookSet extends AbstractMetaOrderByEnumTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    protected function isEnumTypeResolver($enumTypeResolver): bool
    {
        return $enumTypeResolver instanceof CommentOrderByEnumTypeResolver;
    }
}
