<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class GenericTagListObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver|null
     */
    private $taxonomyCustomPostsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface|null
     */
    private $taxonomyTermTypeAPI;
    /**
     * @param \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver $taxonomyCustomPostsFilterInputObjectTypeResolver
     */
    public final function setTaxonomyCustomPostsFilterInputObjectTypeResolver($taxonomyCustomPostsFilterInputObjectTypeResolver) : void
    {
        $this->taxonomyCustomPostsFilterInputObjectTypeResolver = $taxonomyCustomPostsFilterInputObjectTypeResolver;
    }
    protected final function getTaxonomyCustomPostsFilterInputObjectTypeResolver() : TaxonomyCustomPostsFilterInputObjectTypeResolver
    {
        /** @var TaxonomyCustomPostsFilterInputObjectTypeResolver */
        return $this->taxonomyCustomPostsFilterInputObjectTypeResolver = $this->taxonomyCustomPostsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(TaxonomyCustomPostsFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI
     */
    public final function setTaxonomyTermTypeAPI($taxonomyTermTypeAPI) : void
    {
        $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
    }
    protected final function getTaxonomyTermTypeAPI() : TaxonomyTermTypeAPIInterface
    {
        /** @var TaxonomyTermTypeAPIInterface */
        return $this->taxonomyTermTypeAPI = $this->taxonomyTermTypeAPI ?? $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
    }
    protected function getCustomPostsFilterInputObjectTypeResolver() : AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getTaxonomyCustomPostsFilterInputObjectTypeResolver();
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [GenericTagObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'customPosts':
                return $this->__('Custom posts which contain this tag', 'pop-taxonomies');
            case 'customPostCount':
                return $this->__('Number of custom posts which contain this tag', 'pop-taxonomies');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    protected function getQuery($objectTypeResolver, $object, $fieldDataAccessor) : array
    {
        $query = parent::getQuery($objectTypeResolver, $object, $fieldDataAccessor);
        $tag = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'customPosts':
            case 'customPostCount':
                $query['tag-ids'] = [$objectTypeResolver->getID($tag)];
                $query['tag-taxonomy'] = $this->getTaxonomyTermTypeAPI()->getTermTaxonomyName($tag);
                break;
        }
        return $query;
    }
}
