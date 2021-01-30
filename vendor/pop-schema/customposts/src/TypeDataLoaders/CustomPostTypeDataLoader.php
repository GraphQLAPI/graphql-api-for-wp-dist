<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\TypeDataLoaders;

use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;
use PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor;
class CustomPostTypeDataLoader extends \PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader
{
    public function getFilterDataloadingModule() : ?array
    {
        return [\PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::class, \PoPSchema\CustomPosts\ModuleProcessors\CustomPostRelationalFieldDataloadModuleProcessor::MODULE_DATALOAD_RELATIONALFIELDS_UNIONCUSTOMPOSTLIST];
    }
}
