<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemSortInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemPaginationInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemsFilterInputObjectTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface|null
     */
    private $mediaTypeAPI;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver|null
     */
    private $mediaObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByInputObjectTypeResolver|null
     */
    private $mediaItemByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemsFilterInputObjectTypeResolver|null
     */
    private $rootMediaItemsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemPaginationInputObjectTypeResolver|null
     */
    private $rootMediaItemPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemSortInputObjectTypeResolver|null
     */
    private $mediaItemSortInputObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface $mediaTypeAPI
     */
    public final function setMediaTypeAPI($mediaTypeAPI) : void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    protected final function getMediaTypeAPI() : MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI = $this->mediaTypeAPI ?? $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
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
     * @param \PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver $mediaObjectTypeResolver
     */
    public final function setMediaObjectTypeResolver($mediaObjectTypeResolver) : void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    protected final function getMediaObjectTypeResolver() : MediaObjectTypeResolver
    {
        /** @var MediaObjectTypeResolver */
        return $this->mediaObjectTypeResolver = $this->mediaObjectTypeResolver ?? $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByInputObjectTypeResolver $mediaItemByInputObjectTypeResolver
     */
    public final function setMediaItemByInputObjectTypeResolver($mediaItemByInputObjectTypeResolver) : void
    {
        $this->mediaItemByInputObjectTypeResolver = $mediaItemByInputObjectTypeResolver;
    }
    protected final function getMediaItemByInputObjectTypeResolver() : MediaItemByInputObjectTypeResolver
    {
        /** @var MediaItemByInputObjectTypeResolver */
        return $this->mediaItemByInputObjectTypeResolver = $this->mediaItemByInputObjectTypeResolver ?? $this->instanceManager->getInstance(MediaItemByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemsFilterInputObjectTypeResolver $rootMediaItemsFilterInputObjectTypeResolver
     */
    public final function setRootMediaItemsFilterInputObjectTypeResolver($rootMediaItemsFilterInputObjectTypeResolver) : void
    {
        $this->rootMediaItemsFilterInputObjectTypeResolver = $rootMediaItemsFilterInputObjectTypeResolver;
    }
    protected final function getRootMediaItemsFilterInputObjectTypeResolver() : RootMediaItemsFilterInputObjectTypeResolver
    {
        /** @var RootMediaItemsFilterInputObjectTypeResolver */
        return $this->rootMediaItemsFilterInputObjectTypeResolver = $this->rootMediaItemsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootMediaItemsFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Media\TypeResolvers\InputObjectType\RootMediaItemPaginationInputObjectTypeResolver $rootMediaItemPaginationInputObjectTypeResolver
     */
    public final function setRootMediaItemPaginationInputObjectTypeResolver($rootMediaItemPaginationInputObjectTypeResolver) : void
    {
        $this->rootMediaItemPaginationInputObjectTypeResolver = $rootMediaItemPaginationInputObjectTypeResolver;
    }
    protected final function getRootMediaItemPaginationInputObjectTypeResolver() : RootMediaItemPaginationInputObjectTypeResolver
    {
        /** @var RootMediaItemPaginationInputObjectTypeResolver */
        return $this->rootMediaItemPaginationInputObjectTypeResolver = $this->rootMediaItemPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootMediaItemPaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemSortInputObjectTypeResolver $mediaItemSortInputObjectTypeResolver
     */
    public final function setMediaItemSortInputObjectTypeResolver($mediaItemSortInputObjectTypeResolver) : void
    {
        $this->mediaItemSortInputObjectTypeResolver = $mediaItemSortInputObjectTypeResolver;
    }
    protected final function getMediaItemSortInputObjectTypeResolver() : MediaItemSortInputObjectTypeResolver
    {
        /** @var MediaItemSortInputObjectTypeResolver */
        return $this->mediaItemSortInputObjectTypeResolver = $this->mediaItemSortInputObjectTypeResolver ?? $this->instanceManager->getInstance(MediaItemSortInputObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['mediaItem', 'mediaItems', 'mediaItemCount'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'mediaItem':
                return $this->__('Get a media item', 'media');
            case 'mediaItems':
                return $this->__('Get the media items', 'media');
            case 'mediaItemCount':
                return $this->__('Number of media items', 'media');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'mediaItems':
            case 'mediaItem':
                return $this->getMediaObjectTypeResolver();
            case 'mediaItemCount':
                return $this->getIntScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'mediaItems':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            case 'mediaItemCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'mediaItem':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getMediaItemByInputObjectTypeResolver()]);
            case 'mediaItems':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMediaItemsFilterInputObjectTypeResolver(), 'pagination' => $this->getRootMediaItemPaginationInputObjectTypeResolver(), 'sort' => $this->getMediaItemSortInputObjectTypeResolver()]);
            case 'mediaItemCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootMediaItemsFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['mediaItem' => 'by']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
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
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'mediaItemCount':
                return $this->getMediaTypeAPI()->getMediaItemCount($query);
            case 'mediaItems':
                return $this->getMediaTypeAPI()->getMediaItems($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'mediaItem':
                if ($mediaItems = $this->getMediaTypeAPI()->getMediaItems($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $mediaItems[0];
                }
                return null;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
