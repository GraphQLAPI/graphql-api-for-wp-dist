<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\NumericScalarTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByNumericValueOperatorEnumTypeResolver;

class MetaQueryCompareByNumericValueInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\NumericScalarTypeResolver|null
     */
    private $anyBuiltInScalarScalarTypeResolver;
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByNumericValueOperatorEnumTypeResolver|null
     */
    private $metaQueryCompareByNumericValueOperatorEnumTypeResolver;

    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\NumericScalarTypeResolver $anyBuiltInScalarScalarTypeResolver
     */
    final public function setNumericScalarTypeResolver($anyBuiltInScalarScalarTypeResolver): void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    final protected function getNumericScalarTypeResolver(): NumericScalarTypeResolver
    {
        /** @var NumericScalarTypeResolver */
        return $this->anyBuiltInScalarScalarTypeResolver = $this->anyBuiltInScalarScalarTypeResolver ?? $this->instanceManager->getInstance(NumericScalarTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByNumericValueOperatorEnumTypeResolver $metaQueryCompareByNumericValueOperatorEnumTypeResolver
     */
    final public function setMetaQueryCompareByNumericValueOperatorEnumTypeResolver($metaQueryCompareByNumericValueOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByNumericValueOperatorEnumTypeResolver = $metaQueryCompareByNumericValueOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByNumericValueOperatorEnumTypeResolver(): MetaQueryCompareByNumericValueOperatorEnumTypeResolver
    {
        /** @var MetaQueryCompareByNumericValueOperatorEnumTypeResolver */
        return $this->metaQueryCompareByNumericValueOperatorEnumTypeResolver = $this->metaQueryCompareByNumericValueOperatorEnumTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByNumericValueOperatorEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByNumericValueInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'value' => $this->getNumericScalarTypeResolver(),
            'operator' => $this->getMetaQueryCompareByNumericValueOperatorEnumTypeResolver(),
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
                return MetaQueryCompareByOperators::EQUALS;
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
            case 'value':
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
}
