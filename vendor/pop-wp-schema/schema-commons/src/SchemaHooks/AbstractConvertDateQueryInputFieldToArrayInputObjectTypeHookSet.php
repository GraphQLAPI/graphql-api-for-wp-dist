<?php

declare(strict_types=1);

namespace PoPWPSchema\SchemaCommons\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractConvertDateQueryInputFieldToArrayInputObjectTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_TYPE_MODIFIERS,
            \Closure::fromCallable([$this, 'getInputFieldTypeModifiers']),
            10,
            3
        );
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    abstract protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool;

    /**
     * Transform "dateQuery" from a single value to an array of them
     * @param int $inputFieldTypeModifiers
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers(
        $inputFieldTypeModifiers,
        $inputObjectTypeResolver,
        $inputFieldName
    ): int {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        switch ($inputFieldName) {
            case 'dateQuery':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return $inputFieldTypeModifiers;
        }
    }
}
