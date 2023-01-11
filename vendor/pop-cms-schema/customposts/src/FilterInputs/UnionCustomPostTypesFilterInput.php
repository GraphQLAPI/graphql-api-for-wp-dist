<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\FilterInputs;

use PoPCMSSchema\CustomPosts\FilterInput\FilterInputHelper;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;
class UnionCustomPostTypesFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey() : string
    {
        return 'custompost-types';
    }
    /**
     * Make sure the provided postTypes have been whitelisted.
     * @param mixed $value
     * @return mixed
     */
    protected function getValue($value)
    {
        /** @var CustomPostsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
        $value = \array_intersect($value, $moduleConfiguration->getQueryableCustomPostTypes());
        return FilterInputHelper::maybeGetNonExistingCustomPostTypes($value);
    }
}
