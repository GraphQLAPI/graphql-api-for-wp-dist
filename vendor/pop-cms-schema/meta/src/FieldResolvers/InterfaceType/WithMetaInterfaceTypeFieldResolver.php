<?php

declare (strict_types=1);
namespace PoPCMSSchema\Meta\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Meta\TypeResolvers\InterfaceType\WithMetaInterfaceTypeResolver;
class WithMetaInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver|null
     */
    private $anyBuiltInScalarScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\AnyBuiltInScalarScalarTypeResolver $anyBuiltInScalarScalarTypeResolver
     */
    public final function setAnyBuiltInScalarScalarTypeResolver($anyBuiltInScalarScalarTypeResolver) : void
    {
        $this->anyBuiltInScalarScalarTypeResolver = $anyBuiltInScalarScalarTypeResolver;
    }
    protected final function getAnyBuiltInScalarScalarTypeResolver() : AnyBuiltInScalarScalarTypeResolver
    {
        /** @var AnyBuiltInScalarScalarTypeResolver */
        return $this->anyBuiltInScalarScalarTypeResolver = $this->anyBuiltInScalarScalarTypeResolver ?? $this->instanceManager->getInstance(AnyBuiltInScalarScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo() : array
    {
        return [WithMetaInterfaceTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToImplement() : array
    {
        return ['metaValue', 'metaValues'];
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeResolver($fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'metaValue':
                return $this->getAnyBuiltInScalarScalarTypeResolver();
            case 'metaValues':
                return $this->getAnyBuiltInScalarScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($fieldName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($fieldName) : int
    {
        switch ($fieldName) {
            case 'metaValues':
                return SchemaTypeModifiers::IS_ARRAY;
            default:
                return parent::getFieldTypeModifiers($fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($fieldName) : array
    {
        switch ($fieldName) {
            case 'metaValue':
            case 'metaValues':
                return ['key' => $this->getStringScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($fieldName);
        }
    }
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($fieldName, $fieldArgName) : ?string
    {
        switch ($fieldArgName) {
            case 'key':
                return $this->__('The meta key', 'meta');
            default:
                return parent::getFieldArgDescription($fieldName, $fieldArgName);
        }
    }
    /**
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($fieldName, $fieldArgName) : int
    {
        switch ($fieldArgName) {
            case 'key':
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($fieldName, $fieldArgName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string
    {
        switch ($fieldName) {
            case 'metaValue':
                return $this->__('Single meta value. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta');
            case 'metaValues':
                return $this->__('List of meta values. If the key is not allowed, it returns an error; if the key is non-existent, or the value is empty, it returns `null`; otherwise, it returns the meta value.', 'custompostmeta');
            default:
                return parent::getFieldDescription($fieldName);
        }
    }
}
