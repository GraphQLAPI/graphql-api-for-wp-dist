<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\SchemaHooks;

abstract class AbstractCustomPostAddTagFilterInputObjectTypeHookSet extends \PoPCMSSchema\Tags\SchemaHooks\AbstractAddTagFilterInputObjectTypeHookSet
{
    protected function addTagTaxonomyFilterInput() : bool
    {
        return \true;
    }
}
