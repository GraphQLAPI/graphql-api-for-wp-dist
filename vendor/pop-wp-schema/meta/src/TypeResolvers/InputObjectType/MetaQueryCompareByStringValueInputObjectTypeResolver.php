<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByStringValueOperatorEnumTypeResolver;

class MetaQueryCompareByStringValueInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByStringValueOperatorEnumTypeResolver|null
     */
    private $metaQueryCompareByStringValueOperatorEnumTypeResolver;

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    final public function setStringScalarTypeResolver($stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByStringValueOperatorEnumTypeResolver $metaQueryCompareByStringValueOperatorEnumTypeResolver
     */
    final public function setMetaQueryCompareByStringValueOperatorEnumTypeResolver($metaQueryCompareByStringValueOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByStringValueOperatorEnumTypeResolver = $metaQueryCompareByStringValueOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByStringValueOperatorEnumTypeResolver(): MetaQueryCompareByStringValueOperatorEnumTypeResolver
    {
        /** @var MetaQueryCompareByStringValueOperatorEnumTypeResolver */
        return $this->metaQueryCompareByStringValueOperatorEnumTypeResolver = $this->metaQueryCompareByStringValueOperatorEnumTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByStringValueOperatorEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByStringValueInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'value' => $this->getStringScalarTypeResolver(),
            'operator' => $this->getMetaQueryCompareByStringValueOperatorEnumTypeResolver(),
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
