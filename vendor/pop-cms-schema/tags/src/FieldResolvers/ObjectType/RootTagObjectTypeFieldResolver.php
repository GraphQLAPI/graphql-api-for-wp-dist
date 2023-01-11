<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTaxonomyTagListTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\RootTagsFilterInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagByInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagPaginationInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomySortInputObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
class RootTagObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
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
     * @var \PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver|null
     */
    private $tagUnionTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\TypeAPIs\QueryableTaxonomyTagListTypeAPIInterface|null
     */
    private $queryableTaxonomyTagListTypeAPI;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagByInputObjectTypeResolver|null
     */
    private $tagByInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver|null
     */
    private $tagTaxonomyEnumStringScalarTypeResolver;
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
     * @param \PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver $tagUnionTypeResolver
     */
    public final function setTagUnionTypeResolver($tagUnionTypeResolver) : void
    {
        $this->tagUnionTypeResolver = $tagUnionTypeResolver;
    }
    protected final function getTagUnionTypeResolver() : TagUnionTypeResolver
    {
        /** @var TagUnionTypeResolver */
        return $this->tagUnionTypeResolver = $this->tagUnionTypeResolver ?? $this->instanceManager->getInstance(TagUnionTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeAPIs\QueryableTaxonomyTagListTypeAPIInterface $queryableTaxonomyTagListTypeAPI
     */
    public final function setQueryableTaxonomyTagListTypeAPI($queryableTaxonomyTagListTypeAPI) : void
    {
        $this->queryableTaxonomyTagListTypeAPI = $queryableTaxonomyTagListTypeAPI;
    }
    protected final function getQueryableTaxonomyTagListTypeAPI() : QueryableTaxonomyTagListTypeAPIInterface
    {
        /** @var QueryableTaxonomyTagListTypeAPIInterface */
        return $this->queryableTaxonomyTagListTypeAPI = $this->queryableTaxonomyTagListTypeAPI ?? $this->instanceManager->getInstance(QueryableTaxonomyTagListTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\InputObjectType\TagByInputObjectTypeResolver $tagByInputObjectTypeResolver
     */
    public final function setTagByInputObjectTypeResolver($tagByInputObjectTypeResolver) : void
    {
        $this->tagByInputObjectTypeResolver = $tagByInputObjectTypeResolver;
    }
    protected final function getTagByInputObjectTypeResolver() : TagByInputObjectTypeResolver
    {
        /** @var TagByInputObjectTypeResolver */
        return $this->tagByInputObjectTypeResolver = $this->tagByInputObjectTypeResolver ?? $this->instanceManager->getInstance(TagByInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver
     */
    public final function setTagTaxonomyEnumStringScalarTypeResolver($tagTaxonomyEnumStringScalarTypeResolver) : void
    {
        $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
    }
    protected final function getTagTaxonomyEnumStringScalarTypeResolver() : TagTaxonomyEnumStringScalarTypeResolver
    {
        /** @var TagTaxonomyEnumStringScalarTypeResolver */
        return $this->tagTaxonomyEnumStringScalarTypeResolver = $this->tagTaxonomyEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(TagTaxonomyEnumStringScalarTypeResolver::class);
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
        return ['tag', 'tags', 'tagCount', 'tagNames'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'tag':
            case 'tags':
                return $this->getTagUnionTypeResolver();
            case 'tagCount':
                return $this->getIntScalarTypeResolver();
            case 'tagNames':
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
            case 'tagCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'tags':
            case 'tagNames':
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
            case 'tag':
                return $this->__('Retrieve a single post tag', 'pop-post-tags');
            case 'tags':
                return $this->__(' tags', 'pop-post-tags');
            case 'tagCount':
                return $this->__('Number of post tags', 'pop-post-tags');
            case 'tagNames':
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
        $commonFieldArgNameTypeResolvers = ['taxonomy' => $this->getTagTaxonomyEnumStringScalarTypeResolver()];
        switch ($fieldName) {
            case 'tag':
                return \array_merge($fieldArgNameTypeResolvers, $commonFieldArgNameTypeResolvers, ['by' => $this->getTagByInputObjectTypeResolver()]);
            case 'tags':
            case 'tagNames':
                return \array_merge($fieldArgNameTypeResolvers, $commonFieldArgNameTypeResolvers, ['filter' => $this->getRootTagsFilterInputObjectTypeResolver(), 'pagination' => $this->getTagPaginationInputObjectTypeResolver(), 'sort' => $this->getTaxonomySortInputObjectTypeResolver()]);
            case 'tagCount':
                return \array_merge($fieldArgNameTypeResolvers, $commonFieldArgNameTypeResolvers, ['filter' => $this->getRootTagsFilterInputObjectTypeResolver()]);
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
        if ($fieldArgName === 'taxonomy') {
            return SchemaTypeModifiers::MANDATORY;
        }
        switch ([$fieldName => $fieldArgName]) {
            case ['tag' => 'by']:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        if ($fieldArgName === 'taxonomy') {
            return $this->__('Taxonomy of the tag', 'tags');
        }
        switch ([$fieldName => $fieldArgName]) {
            case ['tag' => 'by']:
                return $this->__('Parameter by which to select the tag', 'tags');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
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
        /** @var string */
        $tagTaxonomy = $fieldDataAccessor->getValue('taxonomy');
        switch ($fieldDataAccessor->getFieldName()) {
            case 'tag':
                if ($tags = $this->getQueryableTaxonomyTagListTypeAPI()->getTaxonomyTags($tagTaxonomy, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $tags[0];
                }
                return null;
            case 'tags':
                return $this->getQueryableTaxonomyTagListTypeAPI()->getTaxonomyTags($tagTaxonomy, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'tagNames':
                return $this->getQueryableTaxonomyTagListTypeAPI()->getTaxonomyTags($tagTaxonomy, $query, [QueryOptions::RETURN_TYPE => ReturnTypes::NAMES]);
            case 'tagCount':
                return $this->getQueryableTaxonomyTagListTypeAPI()->getTaxonomyTagCount($tagTaxonomy, $query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
