<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;

interface TaxonomyRegistryInterface
{
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface $taxonomy
     * @param string $serviceDefinitionID
     */
    public function addTaxonomy($taxonomy, $serviceDefinitionID): void;

    /**
     * @param boolean|null $isHierarchical `true` => category, `false` => tag, `null` => categories + tags
     * @return array<string,TaxonomyInterface>
     */
    public function getTaxonomies($isHierarchical = null): array;
}
