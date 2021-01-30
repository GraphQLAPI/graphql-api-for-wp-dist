<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMedia\DirectiveResolvers;

use PoPSchema\CustomPostMedia\Environment;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\BasicDirectives\DirectiveResolvers\AbstractUseDefaultValueIfConditionDirectiveResolver;
class UseDefaultFeaturedImageIDIfConditionDirectiveResolver extends \PoPSchema\BasicDirectives\DirectiveResolvers\AbstractUseDefaultValueIfConditionDirectiveResolver
{
    const DIRECTIVE_NAME = 'defaultFeaturedImage';
    public static function getDirectiveName() : string
    {
        return self::DIRECTIVE_NAME;
    }
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver::class];
    }
    public static function getFieldNamesToApplyTo() : array
    {
        return ['featuredImage'];
    }
    protected function getDefaultValue()
    {
        return \PoPSchema\CustomPostMedia\Environment::getDefaultFeaturedImageID();
    }
}
