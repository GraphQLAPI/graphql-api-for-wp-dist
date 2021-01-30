<?php

declare (strict_types=1);
namespace PoPSchema\PostTags\TypeDataLoaders;

use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
use PoPSchema\Tags\TypeDataLoaders\AbstractTagTypeDataLoader;
class PostTagTypeDataLoader extends \PoPSchema\Tags\TypeDataLoaders\AbstractTagTypeDataLoader
{
    use PostTagAPISatisfiedContractTrait;
    public function getFilterDataloadingModule() : ?array
    {
        return [\PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::class, \PrefixedByPoP\PoP_PostTags_Module_Processor_FieldDataloads::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST];
    }
}
