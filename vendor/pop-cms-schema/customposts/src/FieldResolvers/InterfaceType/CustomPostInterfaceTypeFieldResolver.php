<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InterfaceType\CustomPostInterfaceTypeResolver;
use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
class CustomPostInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver|null
     */
    private $customPostStatusEnumTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver|null
     */
    private $dateTimeScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver|null
     */
    private $htmlScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver|null
     */
    private $queryableInterfaceTypeFieldResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver|null
     */
    private $customPostEnumStringScalarTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver
     */
    public final function setCustomPostStatusEnumTypeResolver($customPostStatusEnumTypeResolver) : void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    protected final function getCustomPostStatusEnumTypeResolver() : CustomPostStatusEnumTypeResolver
    {
        /** @var CustomPostStatusEnumTypeResolver */
        return $this->customPostStatusEnumTypeResolver = $this->customPostStatusEnumTypeResolver ?? $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver $dateTimeScalarTypeResolver
     */
    public final function setDateTimeScalarTypeResolver($dateTimeScalarTypeResolver) : void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    protected final function getDateTimeScalarTypeResolver() : DateTimeScalarTypeResolver
    {
        /** @var DateTimeScalarTypeResolver */
        return $this->dateTimeScalarTypeResolver = $this->dateTimeScalarTypeResolver ?? $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
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
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver $htmlScalarTypeResolver
     */
    public final function setHTMLScalarTypeResolver($htmlScalarTypeResolver) : void
    {
        $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
    }
    protected final function getHTMLScalarTypeResolver() : HTMLScalarTypeResolver
    {
        /** @var HTMLScalarTypeResolver */
        return $this->htmlScalarTypeResolver = $this->htmlScalarTypeResolver ?? $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver $queryableInterfaceTypeFieldResolver
     */
    public final function setQueryableInterfaceTypeFieldResolver($queryableInterfaceTypeFieldResolver) : void
    {
        $this->queryableInterfaceTypeFieldResolver = $queryableInterfaceTypeFieldResolver;
    }
    protected final function getQueryableInterfaceTypeFieldResolver() : QueryableInterfaceTypeFieldResolver
    {
        /** @var QueryableInterfaceTypeFieldResolver */
        return $this->queryableInterfaceTypeFieldResolver = $this->queryableInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(QueryableInterfaceTypeFieldResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver
     */
    public final function setCustomPostEnumStringScalarTypeResolver($customPostEnumStringScalarTypeResolver) : void
    {
        $this->customPostEnumStringScalarTypeResolver = $customPostEnumStringScalarTypeResolver;
    }
    protected final function getCustomPostEnumStringScalarTypeResolver() : CustomPostEnumStringScalarTypeResolver
    {
        /** @var CustomPostEnumStringScalarTypeResolver */
        return $this->customPostEnumStringScalarTypeResolver = $this->customPostEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(CustomPostEnumStringScalarTypeResolver::class);
    }
    /**
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo() : array
    {
        return [CustomPostInterfaceTypeResolver::class];
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getQueryableInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToImplement() : array
    {
        return ['url', 'urlAbsolutePath', 'slug', 'content', 'rawContent', 'status', 'isStatus', 'date', 'dateStr', 'modifiedDate', 'modifiedDateStr', 'title', 'excerpt', 'customPostType'];
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeResolver($fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'isStatus':
                return $this->getBooleanScalarTypeResolver();
            case 'date':
            case 'modifiedDate':
                return $this->getDateTimeScalarTypeResolver();
            case 'rawContent':
            case 'title':
            case 'excerpt':
            case 'dateStr':
            case 'modifiedDateStr':
                return $this->getStringScalarTypeResolver();
            case 'content':
                return $this->getHTMLScalarTypeResolver();
            case 'customPostType':
                return $this->getCustomPostEnumStringScalarTypeResolver();
            case 'status':
                return $this->getCustomPostStatusEnumTypeResolver();
            default:
                return parent::getFieldTypeResolver($fieldName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($fieldName) : int
    {
        /**
         * Please notice that the URL, slug, title and excerpt are nullable,
         * and content is not!
         */
        switch ($fieldName) {
            case 'content':
            case 'rawContent':
            case 'status':
            case 'isStatus':
            case 'date':
            case 'dateStr':
            case 'modifiedDate':
            case 'modifiedDateStr':
            case 'customPostType':
                return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getFieldTypeModifiers($fieldName);
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string
    {
        switch ($fieldName) {
            case 'url':
                return $this->__('Custom post URL', 'customposts');
            case 'urlAbsolutePath':
                return $this->__('Custom post URL path', 'customposts');
            case 'slug':
                return $this->__('Custom post slug', 'customposts');
            case 'content':
                return $this->__('Custom post content, in HTML format', 'customposts');
            case 'rawContent':
                return $this->__('Custom post content, in raw format', 'customposts');
            case 'status':
                return $this->__('Custom post status', 'customposts');
            case 'isStatus':
                return $this->__('Is the custom post in the given status?', 'customposts');
            case 'date':
                return $this->__('Custom post published date', 'customposts');
            case 'dateStr':
                return $this->__('Custom post published date, in String format', 'customposts');
            case 'modifiedDate':
                return $this->__('Custom post modified date', 'customposts');
            case 'modifiedDateStr':
                return $this->__('Custom post modified date, in String format', 'customposts');
            case 'title':
                return $this->__('Custom post title', 'customposts');
            case 'excerpt':
                return $this->__('Custom post excerpt', 'customposts');
            case 'customPostType':
                return $this->__('Custom post type', 'customposts');
            default:
                return parent::getFieldDescription($fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($fieldName) : array
    {
        switch ($fieldName) {
            case 'isStatus':
                return ['status' => $this->getCustomPostStatusEnumTypeResolver()];
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
        switch ([$fieldName => $fieldArgName]) {
            case ['isStatus' => 'status']:
                return $this->__('The status to check if the post has', 'customposts');
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
        switch ([$fieldName => $fieldArgName]) {
            case ['isStatus' => 'status']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($fieldName, $fieldArgName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($fieldName) : ?Component
    {
        switch ($fieldName) {
            case 'date':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE);
            case 'dateStr':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING);
            case 'modifiedDate':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE);
            case 'modifiedDateStr':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING);
            default:
                return parent::getFieldFilterInputContainerComponent($fieldName);
        }
    }
}
