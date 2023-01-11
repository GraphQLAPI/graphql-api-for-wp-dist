<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeQueryableDataLoader;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
class MediaTypeDataLoader extends AbstractObjectTypeQueryableDataLoader
{
    /**
     * @var \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface|null
     */
    private $mediaTypeAPI;
    /**
     * @param \PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface $mediaTypeAPI
     */
    public final function setMediaTypeAPI($mediaTypeAPI) : void
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
    }
    protected final function getMediaTypeAPI() : MediaTypeAPIInterface
    {
        /** @var MediaTypeAPIInterface */
        return $this->mediaTypeAPI = $this->mediaTypeAPI ?? $this->instanceManager->getInstance(MediaTypeAPIInterface::class);
    }
    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs($ids) : array
    {
        return ['include' => $ids];
    }
    /**
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery($query, $options = []) : array
    {
        return $this->getMediaTypeAPI()->getMediaItems($query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @return array<string|int>
     */
    public function executeQueryIDs($query) : array
    {
        $options = [QueryOptions::RETURN_TYPE => ReturnTypes::IDS];
        return $this->executeQuery($query, $options);
    }
}
