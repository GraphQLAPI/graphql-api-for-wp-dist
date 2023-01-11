<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTags\FieldResolvers\ObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\PostTags\TypeResolvers\InputObjectType\PostTagByInputObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\RootTagsFilterInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagPaginationInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
class RootPostTagObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver|null
     */
    private $postTagObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface|null
     */
    private $postTagTypeAPI;
    /**
     * @var \PoPCMSSchema\PostTags\TypeResolvers\InputObjectType\PostTagByInputObjectTypeResolver|null
     */
    private $postTagByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagPaginationInputObjectTypeResolver|null
     */
    private $tagPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver|null
     */
    private $taxonomySortInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\InputObjectType\RootTagsFilterInputObjectTypeResolver|null
     */
    private $rootTagsFilterInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver $postTagObjectTypeResolver
     */
    public final function setPostTagObjectTypeResolver($postTagObjectTypeResolver) : void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    protected final function getPostTagObjectTypeResolver() : PostTagObjectTypeResolver
    {
        /** @var PostTagObjectTypeResolver */
        return $this->postTagObjectTypeResolver = $this->postTagObjectTypeResolver ?? $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface $postTagTypeAPI
     */
    public final function setPostTagTypeAPI($postTagTypeAPI) : void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    protected final function getPostTagTypeAPI() : PostTagTypeAPIInterface
    {
        /** @var PostTagTypeAPIInterface */
        return $this->postTagTypeAPI = $this->postTagTypeAPI ?? $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\PostTags\TypeResolvers\InputObjectType\PostTagByInputObjectTypeResolver $postTagByInputObjectTypeResolver
     */
    public final function setPostTagByInputObjectTypeResolver($postTagByInputObjectTypeResolver) : void
    {
        $this->postTagByInputObjectTypeResolver = $postTagByInputObjectTypeResolver;
    }
    protected final function getPostTagByInputObjectTypeResolver() : PostTagByInputObjectTypeResolver
    {
        /** @var PostTagByInputObjectTypeResolver */
        return $this->postTagByInputObjectTypeResolver = $this->postTagByInputObjectTypeResolver ?? $this->instanceManager->getInstance(PostTagByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagPaginationInputObjectTypeResolver $tagPaginationInputObjectTypeResolver
     */
    public final function setTagPaginationInputObjectTypeResolver($tagPaginationInputObjectTypeResolver) : void
    {
        $this->tagPaginationInputObjectTypeResolver = $tagPaginationInputObjectTypeResolver;
    }
    protected final function getTagPaginationInputObjectTypeResolver() : TagPaginationInputObjectTypeResolver
    {
        /** @var TagPaginationInputObjectTypeResolver */
        return $this->tagPaginationInputObjectTypeResolver = $this->tagPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(TagPaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver $taxonomySortInputObjectTypeResolver
     */
    public final function setTaxonomySortInputObjectTypeResolver($taxonomySortInputObjectTypeResolver) : void
    {
        $this->taxonomySortInputObjectTypeResolver = $taxonomySortInputObjectTypeResolver;
    }
    protected final function getTaxonomySortInputObjectTypeResolver() : TaxonomySortInputObjectTypeResolver
    {
        /** @var TaxonomySortInputObjectTypeResolver */
        return $this->taxonomySortInputObjectTypeResolver = $this->taxonomySortInputObjectTypeResolver ?? $this->instanceManager->getInstance(TaxonomySortInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\InputObjectType\RootTagsFilterInputObjectTypeResolver $rootTagsFilterInputObjectTypeResolver
     */
    public final function setRootTagsFilterInputObjectTypeResolver($rootTagsFilterInputObjectTypeResolver) : void
    {
        $this->rootTagsFilterInputObjectTypeResolver = $rootTagsFilterInputObjectTypeResolver;
    }
    protected final function getRootTagsFilterInputObjectTypeResolver() : RootTagsFilterInputObjectTypeResolver
    {
        /** @var RootTagsFilterInputObjectTypeResolver */
        return $this->rootTagsFilterInputObjectTypeResolver = $this->rootTagsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootTagsFilterInputObjectTypeResolver::class);
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
        return ['postTag', 'postTags', 'postTagCount', 'postTagNames'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'postTag':
            case 'postTags':
                return $this->getPostTagObjectTypeResolver();
            case 'postTagCount':
                return $this->getIntScalarTypeResolver();
            case 'postTagNames':
                return $this->getStringScalarTypeResolver();
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
            case 'postTagCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'postTags':
            case 'postTagNames':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'postTag':
                return $this->__('Retrieve a single post tag', 'pop-post-tags');
            case 'postTags':
                return $this->__('Post tags', 'pop-post-tags');
            case 'postTagCount':
                return $this->__('Number of post tags', 'pop-post-tags');
            case 'postTagNames':
                return $this->__('Names of the post tags', 'pop-post-tags');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
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
            case 'postTag':
                return \array_merge($fieldArgNameTypeResolvers, ['by' => $this->getPostTagByInputObjectTypeResolver()]);
            case 'postTags':
            case 'postTagNames':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootTagsFilterInputObjectTypeResolver(), 'pagination' => $this->getTagPaginationInputObjectTypeResolver(), 'sort' => $this->getTaxonomySortInputObjectTypeResolver()]);
            case 'postTagCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getRootTagsFilterInputObjectTypeResolver()]);
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
            case ['postTag' => 'by']:
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
            case 'postTag':
                if ($tags = $this->getPostTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $tags[0];
                }
                return null;
            case 'postTags':
                return $this->getPostTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'postTagNames':
                return $this->getPostTagTypeAPI()->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'postTagCount':
                return $this->getPostTagTypeAPI()->getTagCount($query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
