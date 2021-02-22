<?php

declare (strict_types=1);
namespace PoPSchema\Posts\ConditionalOnEnvironment\AddPostTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\Posts\TypeResolverPickers\AbstractPostTypeResolverPicker;
class PostCustomPostTypeResolverPicker extends \PoPSchema\Posts\TypeResolverPickers\AbstractPostTypeResolverPicker
{
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class];
    }
}
