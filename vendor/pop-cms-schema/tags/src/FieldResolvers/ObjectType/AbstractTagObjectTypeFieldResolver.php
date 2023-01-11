<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use PoPCMSSchema\Tags\FieldResolvers\InterfaceType\TagInterfaceTypeFieldResolver;
use PoPCMSSchema\Tags\ModuleContracts\TagAPIRequestedContractObjectTypeFieldResolverInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
abstract class AbstractTagObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements TagAPIRequestedContractObjectTypeFieldResolverInterface
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver|null
     */
    private $queryableInterfaceTypeFieldResolver;
    /**
     * @var \PoPCMSSchema\Tags\FieldResolvers\InterfaceType\TagInterfaceTypeFieldResolver|null
     */
    private $tagInterfaceTypeFieldResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    public final function setIntScalarTypeResolver($intScalarTypeResolver) : void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    protected final function getIntScalarTypeResolver() : IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
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
     * @param \PoPCMSSchema\Tags\FieldResolvers\InterfaceType\TagInterfaceTypeFieldResolver $tagInterfaceTypeFieldResolver
     */
    public final function setTagInterfaceTypeFieldResolver($tagInterfaceTypeFieldResolver) : void
    {
        $this->tagInterfaceTypeFieldResolver = $tagInterfaceTypeFieldResolver;
    }
    protected final function getTagInterfaceTypeFieldResolver() : TagInterfaceTypeFieldResolver
    {
        /** @var TagInterfaceTypeFieldResolver */
        return $this->tagInterfaceTypeFieldResolver = $this->tagInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(TagInterfaceTypeFieldResolver::class);
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getQueryableInterfaceTypeFieldResolver(), $this->getTagInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return [
            // Queryable interface
            'url',
            'urlAbsolutePath',
            'slug',
            // Tag interface
            'name',
            'description',
            'count',
        ];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'url':
                return $this->__('Tag URL', 'pop-tags');
            case 'urlAbsolutePath':
                return $this->__('Tag URL path', 'pop-tags');
            case 'slug':
                return $this->__('Tag slug', 'pop-tags');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $tagTypeAPI = $this->getTagTypeAPI();
        $tag = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
                /** @var string */
                return $tagTypeAPI->getTagURL($tag);
            case 'urlAbsolutePath':
                /** @var string */
                return $tagTypeAPI->getTagURLPath($tag);
            case 'name':
                /** @var string */
                return $tagTypeAPI->getTagName($tag);
            case 'slug':
                /** @var string */
                return $tagTypeAPI->getTagSlug($tag);
            case 'description':
                /** @var string */
                return $tagTypeAPI->getTagDescription($tag);
            case 'count':
                /** @var int */
                return $tagTypeAPI->getTagItemCount($tag);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
