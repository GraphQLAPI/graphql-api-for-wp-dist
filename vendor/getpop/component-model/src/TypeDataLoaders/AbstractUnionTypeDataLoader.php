<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeDataLoaders;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
abstract class AbstractUnionTypeDataLoader extends \PoP\ComponentModel\TypeDataLoaders\AbstractTypeDataLoader
{
    protected abstract function getUnionTypeResolverClass() : string;
    /**
     * Iterate through all unionTypes and delegate to each resolving the IDs each of them can resolve
     *
     * @param array $ids
     * @return array
     */
    public function getObjects(array $ids) : array
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $unionTypeResolverClass = $this->getUnionTypeResolverClass();
        $unionTypeResolver = $instanceManager->getInstance($unionTypeResolverClass);
        $resultItemIDTargetTypeResolvers = $unionTypeResolver->getResultItemIDTargetTypeResolvers($ids);
        // Organize all IDs by same resolverClass
        $typeResolverClassResultItemIDs = [];
        foreach ($resultItemIDTargetTypeResolvers as $resultItemID => $targetTypeResolver) {
            $typeResolverClassResultItemIDs[\get_class($targetTypeResolver)][] = $resultItemID;
        }
        // Load all objects by each corresponding typeResolver
        $resultItems = [];
        foreach ($typeResolverClassResultItemIDs as $targetTypeResolverClass => $resultItemIDs) {
            $targetTypeResolver = $instanceManager->getInstance($targetTypeResolverClass);
            $targetTypeDataLoaderClass = $targetTypeResolver->getTypeDataLoaderClass();
            $targetTypeDataLoader = $instanceManager->getInstance($targetTypeDataLoaderClass);
            $resultItems = \array_merge($resultItems, $targetTypeDataLoader->getObjects($resultItemIDs));
        }
        return $resultItems;
    }
}
