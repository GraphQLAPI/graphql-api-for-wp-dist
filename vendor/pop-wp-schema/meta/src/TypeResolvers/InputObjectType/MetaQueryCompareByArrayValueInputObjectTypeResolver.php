<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByArrayValueOperatorEnumTypeResolver;

class MetaQueryCompareByArrayValueInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver|null
     */
    private $anyBuiltInScalarScalarTypeResolver;
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByArrayValueOperatorEnumTypeResolver|null
     */
    private $metaQueryCompareByArrayValueOperatorEnumTypeResolver;

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver
     */
    final public function setAnyBuiltInScalarScalarTypeResolver($anyBuiltInScalarScalarTypeResolver): void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getAnyBuiltInScalarScalarTypeResolver(): AnyBuiltInScalarScalarTypeResolver
    {
        /** @var AnyBuiltInScalarScalarTypeResolver */
        return $this->anyBuiltInScalarScalarTypeResolver = $this->anyBuiltInScalarScalarTypeResolver ?? $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByArrayValueOperatorEnumTypeResolver $metaQueryCompareByArrayValueOperatorEnumTypeResolver
     */
    final public function setMetaQueryCompareByArrayValueOperatorEnumTypeResolver($metaQueryCompareByArrayValueOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByArrayValueOperatorEnumTypeResolver = $metaQueryCompareByArrayValueOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByArrayValueOperatorEnumTypeResolver(): MetaQueryCompareByArrayValueOperatorEnumTypeResolver
    {
        /** @var MetaQueryCompareByArrayValueOperatorEnumTypeResolver */
        return $this->metaQueryCompareByArrayValueOperatorEnumTypeResolver = $this->metaQueryCompareByArrayValueOperatorEnumTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByArrayValueOperatorEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByArrayValueInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'value' => $this->getAnyBuiltInScalarScalarTypeResolver(),
            'operator' => $this->getMetaQueryCompareByArrayValueOperatorEnumTypeResolver(),
        ];
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName): ?string
    {
        switch ($inputFieldName) {
            case 'value':
                return $this->__('Custom field value', 'meta');
            case 'operator':
                return $this->__('The operator to compare against', 'meta');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }

    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'operator':
                return MetaQueryCompareByOperators::IN;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName): int
    {
        switch ($inputFieldName) {
            case 'operator':
                return SchemaTypeModifiers::MANDATORY;
            case 'value':
                return SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
}
