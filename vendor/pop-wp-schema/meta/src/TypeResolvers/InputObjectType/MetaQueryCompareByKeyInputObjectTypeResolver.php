<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByKeyOperatorEnumTypeResolver;

class MetaQueryCompareByKeyInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByKeyOperatorEnumTypeResolver|null
     */
    private $metaQueryCompareByKeyOperatorEnumTypeResolver;

    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryCompareByKeyOperatorEnumTypeResolver $metaQueryCompareByKeyOperatorEnumTypeResolver
     */
    final public function setMetaQueryCompareByKeyOperatorEnumTypeResolver($metaQueryCompareByKeyOperatorEnumTypeResolver): void
    {
        $this->metaQueryCompareByKeyOperatorEnumTypeResolver = $metaQueryCompareByKeyOperatorEnumTypeResolver;
    }
    final protected function getMetaQueryCompareByKeyOperatorEnumTypeResolver(): MetaQueryCompareByKeyOperatorEnumTypeResolver
    {
        /** @var MetaQueryCompareByKeyOperatorEnumTypeResolver */
        return $this->metaQueryCompareByKeyOperatorEnumTypeResolver = $this->metaQueryCompareByKeyOperatorEnumTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByKeyOperatorEnumTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByKeyInput';
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'operator' => $this->getMetaQueryCompareByKeyOperatorEnumTypeResolver(),
        ];
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName): ?string
    {
        switch ($inputFieldName) {
            case 'operator':
                return $this->__('The operator to compare against', 'meta');
            default:
                return parent::getInputFieldDescription($inputFieldName);
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
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
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
                return MetaQueryCompareByOperators::EXISTS;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
}
