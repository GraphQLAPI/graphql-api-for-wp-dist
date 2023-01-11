<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPWPSchema\SchemaCommons\SchemaHooks\AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet;

class ConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractCustomPostsFilterInputObjectTypeResolver;
    }
}
