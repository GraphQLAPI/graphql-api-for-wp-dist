<?php

declare (strict_types=1);
namespace PoPCMSSchema\TaxonomyMeta\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Meta\FieldResolvers\ObjectType\AbstractWithMetaObjectTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\ObjectType\AbstractTaxonomyObjectTypeResolver;
use PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface;
class TaxonomyObjectTypeFieldResolver extends AbstractWithMetaObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface|null
     */
    private $taxonomyMetaTypeAPI;
    /**
     * @param \PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface $taxonomyMetaTypeAPI
     */
    public final function setTaxonomyMetaTypeAPI($taxonomyMetaTypeAPI) : void
    {
        $this->taxonomyMetaTypeAPI = $taxonomyMetaTypeAPI;
    }
    protected final function getTaxonomyMetaTypeAPI() : TaxonomyMetaTypeAPIInterface
    {
        /** @var TaxonomyMetaTypeAPIInterface */
        return $this->taxonomyMetaTypeAPI = $this->taxonomyMetaTypeAPI ?? $this->instanceManager->getInstance(TaxonomyMetaTypeAPIInterface::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [AbstractTaxonomyObjectTypeResolver::class];
    }
    protected function getMetaTypeAPI() : MetaTypeAPIInterface
    {
        return $this->getTaxonomyMetaTypeAPI();
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
        $taxonomy = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                return $this->getTaxonomyMetaTypeAPI()->getTaxonomyTermMeta($taxonomy, $fieldDataAccessor->getValue('key'), $fieldDataAccessor->getFieldName() === 'metaValue');
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
