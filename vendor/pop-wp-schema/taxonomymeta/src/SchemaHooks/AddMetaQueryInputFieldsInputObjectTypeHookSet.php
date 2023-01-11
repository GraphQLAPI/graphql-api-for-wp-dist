<?php

declare(strict_types=1);

namespace PoPWPSchema\TaxonomyMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Taxonomies\TypeResolvers\InputObjectType\AbstractTaxonomiesFilterInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;
use PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType\TaxonomyMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    /**
     * @var \PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType\TaxonomyMetaQueryInputObjectTypeResolver|null
     */
    private $taxonomyMetaQueryInputObjectTypeResolver;

    /**
     * @param \PoPWPSchema\TaxonomyMeta\TypeResolvers\InputObjectType\TaxonomyMetaQueryInputObjectTypeResolver $taxonomyMetaQueryInputObjectTypeResolver
     */
    final public function setTaxonomyMetaQueryInputObjectTypeResolver($taxonomyMetaQueryInputObjectTypeResolver): void
    {
        $this->taxonomyMetaQueryInputObjectTypeResolver = $taxonomyMetaQueryInputObjectTypeResolver;
    }
    final protected function getTaxonomyMetaQueryInputObjectTypeResolver(): TaxonomyMetaQueryInputObjectTypeResolver
    {
        /** @var TaxonomyMetaQueryInputObjectTypeResolver */
        return $this->taxonomyMetaQueryInputObjectTypeResolver = $this->taxonomyMetaQueryInputObjectTypeResolver ?? $this->instanceManager->getInstance(TaxonomyMetaQueryInputObjectTypeResolver::class);
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getTaxonomyMetaQueryInputObjectTypeResolver();
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractTaxonomiesFilterInputObjectTypeResolver;
    }
}
