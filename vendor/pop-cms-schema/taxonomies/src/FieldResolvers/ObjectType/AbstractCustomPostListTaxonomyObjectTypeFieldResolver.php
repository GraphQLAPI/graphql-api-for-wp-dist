<?php

declare (strict_types=1);
namespace PoPCMSSchema\Taxonomies\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPosts\FieldResolvers\ObjectType\AbstractCustomPostListObjectTypeFieldResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver;
abstract class AbstractCustomPostListTaxonomyObjectTypeFieldResolver extends AbstractCustomPostListObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\TaxonomyCustomPostsFilterInputObjectTypeResolver|null
     */
    private $taxonomyCustomPostsFilterInputObjectTypeResolver;
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
    protected function getCustomPostsFilterInputObjectTypeResolver() : AbstractCustomPostsFilterInputObjectTypeResolver
    {
        return $this->getTaxonomyCustomPostsFilterInputObjectTypeResolver();
    }
}
