<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\SchemaHooks;

use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\RootCustomPostsFilterInputObjectTypeResolver;
class RootCustomPostsAddTagFilterInputObjectTypeHookSet extends \PoPCMSSchema\Tags\SchemaHooks\AbstractCustomPostAddTagFilterInputObjectTypeHookSet
{
    protected function getInputObjectTypeResolverClass() : string
    {
        return RootCustomPostsFilterInputObjectTypeResolver::class;
    }
}
