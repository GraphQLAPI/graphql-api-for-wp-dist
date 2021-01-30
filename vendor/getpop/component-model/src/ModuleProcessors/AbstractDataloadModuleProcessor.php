<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ModuleProcessors;

abstract class AbstractDataloadModuleProcessor extends \PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor implements \PoP\ComponentModel\ModuleProcessors\DataloadingModuleInterface
{
    use DataloadModuleProcessorTrait;
}
