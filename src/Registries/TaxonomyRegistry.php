<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Registries;

use GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface;

class TaxonomyRegistry implements TaxonomyRegistryInterface
{
    /**
     * @var array<string,TaxonomyInterface>
     */
    protected $taxonomies = [];

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Taxonomies\TaxonomyInterface $taxonomy
     * @param string $serviceDefinitionID
     */
    public function addTaxonomy(
        $taxonomy,
        $serviceDefinitionID
    ): void {
        $this->taxonomies[$serviceDefinitionID] = $taxonomy;
    }

    /**
     * @param boolean|null $isHierarchical `true` => category, `false` => tag, `null` => categories + tags
     * @return array<string,TaxonomyInterface>
     */
    public function getTaxonomies($isHierarchical = null): array
    {
        if ($isHierarchical !== null) {
            return array_filter(
                $this->taxonomies,
                function (TaxonomyInterface $taxonomy) use ($isHierarchical) {
                    return ($isHierarchical && $taxonomy->isHierarchical()) || (!$isHierarchical && !$taxonomy->isHierarchical());
                }
            );
        }
        return $this->taxonomies;
    }
}
