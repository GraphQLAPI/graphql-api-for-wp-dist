<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;

class MetaQueryCompareByOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByKeyInputObjectTypeResolver|null
     */
    private $metaQueryCompareByKeyInputObjectTypeResolver;
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByNumericValueInputObjectTypeResolver|null
     */
    private $metaQueryCompareByNumericValueInputObjectTypeResolver;
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByStringValueInputObjectTypeResolver|null
     */
    private $metaQueryCompareByStringValueInputObjectTypeResolver;
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByArrayValueInputObjectTypeResolver|null
     */
    private $metaQueryCompareByArrayValueInputObjectTypeResolver;

    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByKeyInputObjectTypeResolver $metaQueryCompareByKeyInputObjectTypeResolver
     */
    final public function setMetaQueryCompareByKeyInputObjectTypeResolver($metaQueryCompareByKeyInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByKeyInputObjectTypeResolver = $metaQueryCompareByKeyInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByKeyInputObjectTypeResolver(): MetaQueryCompareByKeyInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByKeyInputObjectTypeResolver */
        return $this->metaQueryCompareByKeyInputObjectTypeResolver = $this->metaQueryCompareByKeyInputObjectTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByKeyInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByNumericValueInputObjectTypeResolver $metaQueryCompareByNumericValueInputObjectTypeResolver
     */
    final public function setMetaQueryCompareByNumericValueInputObjectTypeResolver($metaQueryCompareByNumericValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByNumericValueInputObjectTypeResolver = $metaQueryCompareByNumericValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByNumericValueInputObjectTypeResolver(): MetaQueryCompareByNumericValueInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByNumericValueInputObjectTypeResolver */
        return $this->metaQueryCompareByNumericValueInputObjectTypeResolver = $this->metaQueryCompareByNumericValueInputObjectTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByNumericValueInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByStringValueInputObjectTypeResolver $metaQueryCompareByStringValueInputObjectTypeResolver
     */
    final public function setMetaQueryCompareByStringValueInputObjectTypeResolver($metaQueryCompareByStringValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByStringValueInputObjectTypeResolver = $metaQueryCompareByStringValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByStringValueInputObjectTypeResolver(): MetaQueryCompareByStringValueInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByStringValueInputObjectTypeResolver */
        return $this->metaQueryCompareByStringValueInputObjectTypeResolver = $this->metaQueryCompareByStringValueInputObjectTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByStringValueInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByArrayValueInputObjectTypeResolver $metaQueryCompareByArrayValueInputObjectTypeResolver
     */
    final public function setMetaQueryCompareByArrayValueInputObjectTypeResolver($metaQueryCompareByArrayValueInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByArrayValueInputObjectTypeResolver = $metaQueryCompareByArrayValueInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByArrayValueInputObjectTypeResolver(): MetaQueryCompareByArrayValueInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByArrayValueInputObjectTypeResolver */
        return $this->metaQueryCompareByArrayValueInputObjectTypeResolver = $this->metaQueryCompareByArrayValueInputObjectTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByArrayValueInputObjectTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'MetaQueryCompareByInput';
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'key' => $this->getMetaQueryCompareByKeyInputObjectTypeResolver(),
            'numericValue' => $this->getMetaQueryCompareByNumericValueInputObjectTypeResolver(),
            'stringValue' => $this->getMetaQueryCompareByStringValueInputObjectTypeResolver(),
            'arrayValue' => $this->getMetaQueryCompareByArrayValueInputObjectTypeResolver(),
        ];
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName): ?string
    {
        switch ($inputFieldName) {
            case 'key':
                return $this->__('Compare against the meta key', 'meta');
            case 'numericValue':
                return $this->__('Compare against a numeric meta value', 'meta');
            case 'stringValue':
                return $this->__('Compare against a string meta value', 'meta');
            case 'arrayValue':
                return $this->__('Compare against an array meta value', 'meta');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
}
