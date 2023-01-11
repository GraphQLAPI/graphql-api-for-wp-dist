<?php

declare(strict_types=1);

namespace PoPWPSchema\Comments\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\AbstractCommentsFilterInputObjectTypeResolver;
use PoPWPSchema\SchemaCommons\SchemaHooks\AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet;

class ConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet
{
    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractCommentsFilterInputObjectTypeResolver;
    }
}
