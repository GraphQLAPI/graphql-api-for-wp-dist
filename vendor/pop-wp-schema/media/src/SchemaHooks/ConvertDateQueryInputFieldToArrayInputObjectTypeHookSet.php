<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\AbstractMediaItemsFilterInputObjectTypeResolver;
use PoPWPSchema\SchemaCommons\SchemaHooks\AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet;

class ConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractMediaItemsFilterInputObjectTypeResolver;
    }
}
