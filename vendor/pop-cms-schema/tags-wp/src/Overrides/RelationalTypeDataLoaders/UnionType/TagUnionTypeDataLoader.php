<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\Tags\Module as TagsModule;
use PoPCMSSchema\Tags\ModuleConfiguration as TagsModuleConfiguration;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListTypeDataLoader;
use PoPCMSSchema\Tags\RelationalTypeDataLoaders\UnionType\TagUnionTypeDataLoader as UpstreamTagUnionTypeDataLoader;
use PoP\ComponentModel\App;

/**
 * Retrieve the union of tags, for different GraphQL types
 * (i.e. for different taxonomies), with a single method execution
 */
class TagUnionTypeDataLoader extends UpstreamTagUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListTypeDataLoader|null
     */
    private $queryableTagListTypeDataLoader;

    /**
     * @param \PoPCMSSchema\Tags\RelationalTypeDataLoaders\ObjectType\QueryableTagListTypeDataLoader $queryableTagListTypeDataLoader
     */
    final public function setQueryableTagListTypeDataLoader($queryableTagListTypeDataLoader): void
    {
        $this->queryableTagListTypeDataLoader = $queryableTagListTypeDataLoader;
    }
    final protected function getQueryableTagListTypeDataLoader(): QueryableTagListTypeDataLoader
    {
        /** @var QueryableTagListTypeDataLoader */
        return $this->queryableTagListTypeDataLoader = $this->queryableTagListTypeDataLoader ?? $this->instanceManager->getInstance(QueryableTagListTypeDataLoader::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs($ids): array
    {
        $query = $this->getQueryableTagListTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all taxonomies from the member typeResolvers
        /** @var TagsModuleConfiguration */
        $moduleConfiguration = App::getModule(TagsModule::class)->getConfiguration();
        $query['taxonomy'] = $moduleConfiguration->getQueryableTagTaxonomies();

        return $query;
    }

    /**
     * Override function to execute a single call to the DB,
     * instead of one per type.
     *
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids): array
    {
        $query = $this->getQueryToRetrieveObjectsForIDs($ids);
        return $this->getQueryableTagListTypeDataLoader()->executeQuery($query);
    }
}
