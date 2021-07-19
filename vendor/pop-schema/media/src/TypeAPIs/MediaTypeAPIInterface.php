<?php

declare (strict_types=1);
namespace PoPSchema\Media\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface MediaTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Media
     * @param object $object
     */
    public function isInstanceOfMediaType($object) : bool;
    /**
     * @param string|int $image_id
     */
    public function getImageSrc($image_id, ?string $size = null) : ?string;
    /**
     * @param string|int $image_id
     */
    public function getImageProperties($image_id, ?string $size = null) : ?array;
    public function getMediaElements(array $query, array $options = []) : array;
    /**
     * @return string|int
     * @param object $media
     */
    public function getMediaElementId($media);
}
