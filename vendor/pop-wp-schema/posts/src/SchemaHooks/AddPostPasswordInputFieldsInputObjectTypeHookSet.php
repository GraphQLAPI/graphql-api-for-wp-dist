<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\PostsFilterInputObjectTypeResolverInterface;
use PoPWPSchema\CustomPosts\SchemaHooks\AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet;

class AddPostPasswordInputFieldsInputObjectTypeHookSet extends AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof PostsFilterInputObjectTypeResolverInterface;
    }
}
