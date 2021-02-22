<?php

declare (strict_types=1);
namespace PoPSchema\Pages\ConditionalOnEnvironment\AddPageTypeToCustomPostUnionTypes\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\Pages\TypeResolverPickers\AbstractPageTypeResolverPicker;
class PageCustomPostTypeResolverPicker extends \PoPSchema\Pages\TypeResolverPickers\AbstractPageTypeResolverPicker
{
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class];
    }
}
