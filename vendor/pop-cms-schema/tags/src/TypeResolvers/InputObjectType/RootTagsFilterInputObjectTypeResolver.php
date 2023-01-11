<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\TypeResolvers\InputObjectType;

class RootTagsFilterInputObjectTypeResolver extends \PoPCMSSchema\Tags\TypeResolvers\InputObjectType\AbstractTagsFilterInputObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'RootTagsFilterInput';
    }
}
