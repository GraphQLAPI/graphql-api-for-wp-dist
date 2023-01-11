<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\ObjectMutationPayloadObjectTypeDataLoader;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractTransientEntityOperationPayloadObjectTypeResolver;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
abstract class AbstractObjectMutationPayloadObjectTypeResolver extends AbstractTransientEntityOperationPayloadObjectTypeResolver
{
    /**
     * @var \PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\ObjectMutationPayloadObjectTypeDataLoader|null
     */
    private $objectMutationPayloadObjectTypeDataLoader;
    /**
     * @param \PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\ObjectMutationPayloadObjectTypeDataLoader $objectMutationPayloadObjectTypeDataLoader
     */
    public final function setObjectMutationPayloadObjectTypeDataLoader($objectMutationPayloadObjectTypeDataLoader) : void
    {
        $this->objectMutationPayloadObjectTypeDataLoader = $objectMutationPayloadObjectTypeDataLoader;
    }
    protected final function getObjectMutationPayloadObjectTypeDataLoader() : ObjectMutationPayloadObjectTypeDataLoader
    {
        /** @var ObjectMutationPayloadObjectTypeDataLoader */
        return $this->objectMutationPayloadObjectTypeDataLoader = $this->objectMutationPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(ObjectMutationPayloadObjectTypeDataLoader::class);
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getObjectMutationPayloadObjectTypeDataLoader();
    }
}
