<?php

declare (strict_types=1);
namespace PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\QueriedObject\TypeResolvers\InterfaceType\QueryableInterfaceTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
class QueryableInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver|null
     */
    private $urlScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver|null
     */
    private $urlAbsolutePathScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver $urlScalarTypeResolver
     */
    public final function setURLScalarTypeResolver($urlScalarTypeResolver) : void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    protected final function getURLScalarTypeResolver() : URLScalarTypeResolver
    {
        /** @var URLScalarTypeResolver */
        return $this->urlScalarTypeResolver = $this->urlScalarTypeResolver ?? $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLAbsolutePathScalarTypeResolver $urlAbsolutePathScalarTypeResolver
     */
    public final function setURLAbsolutePathScalarTypeResolver($urlAbsolutePathScalarTypeResolver) : void
    {
        $this->urlAbsolutePathScalarTypeResolver = $urlAbsolutePathScalarTypeResolver;
    }
    protected final function getURLAbsolutePathScalarTypeResolver() : URLAbsolutePathScalarTypeResolver
    {
        /** @var URLAbsolutePathScalarTypeResolver */
        return $this->urlAbsolutePathScalarTypeResolver = $this->urlAbsolutePathScalarTypeResolver ?? $this->instanceManager->getInstance(URLAbsolutePathScalarTypeResolver::class);
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
        return [QueryableInterfaceTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToImplement() : array
    {
        return ['url', 'urlAbsolutePath', 'slug'];
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeResolver($fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'url':
                return $this->getURLScalarTypeResolver();
            case 'urlAbsolutePath':
                return $this->getURLAbsolutePathScalarTypeResolver();
            case 'slug':
                return $this->getStringScalarTypeResolver();
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
            case 'url':
            case 'urlAbsolutePath':
            case 'slug':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($fieldName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string
    {
        switch ($fieldName) {
            case 'url':
                return $this->__('URL to query the object', 'queriedobject');
            case 'urlAbsolutePath':
                return $this->__('URL path to query the object', 'queriedobject');
            case 'slug':
                return $this->__('URL\'s slug', 'queriedobject');
            default:
                return parent::getFieldDescription($fieldName);
        }
    }
}
