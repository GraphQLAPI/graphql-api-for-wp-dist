<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\TypeResolvers\ObjectType;

use PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\GenericErrorPayloadObjectTypeDataLoader;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
class GenericErrorPayloadObjectTypeResolver extends \PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractErrorPayloadObjectTypeResolver
{
    /**
     * @var \PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\GenericErrorPayloadObjectTypeDataLoader|null
     */
    private $genericErrorPayloadObjectTypeDataLoader;
    /**
     * @param \PoPSchema\SchemaCommons\RelationalTypeDataLoaders\ObjectType\GenericErrorPayloadObjectTypeDataLoader $genericErrorPayloadObjectTypeDataLoader
     */
    public final function setGenericErrorPayloadObjectTypeDataLoader($genericErrorPayloadObjectTypeDataLoader) : void
    {
        $this->genericErrorPayloadObjectTypeDataLoader = $genericErrorPayloadObjectTypeDataLoader;
    }
    protected final function getGenericErrorPayloadObjectTypeDataLoader() : GenericErrorPayloadObjectTypeDataLoader
    {
        /** @var GenericErrorPayloadObjectTypeDataLoader */
        return $this->genericErrorPayloadObjectTypeDataLoader = $this->genericErrorPayloadObjectTypeDataLoader ?? $this->instanceManager->getInstance(GenericErrorPayloadObjectTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'GenericErrorPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Generic error payload', 'schema-commons');
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getGenericErrorPayloadObjectTypeDataLoader();
    }
}
