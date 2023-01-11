<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\TypeAPIs;

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
     * @param string|int|object $mediaItemObjectOrID
     */
    public function getMediaItemSrc($mediaItemObjectOrID) : ?string;
    /**
     * @param string|int|object $mediaItemObjectOrID
     */
    public function getMediaItemSrcPath($mediaItemObjectOrID) : ?string;
    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSrc($mediaItemObjectOrID, $size = null) : ?string;
    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSrcPath($mediaItemObjectOrID, $size = null) : ?string;
    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSrcSet($mediaItemObjectOrID, $size = null) : ?string;
    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSizes($mediaItemObjectOrID, $size = null) : ?string;
    /**
     * @return array{src:string,width:?int,height:?int}
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageProperties($mediaItemObjectOrID, $size = null) : ?array;
    /**
     * Get the media item with provided ID or, if it doesn't exist, null
     * @param int|string $id
     */
    public function getMediaItem($id);
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItems($query, $options = []) : array;
    /**
     * @param int|string $id
     */
    public function mediaItemExists($id) : bool;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItemCount($query, $options = []) : int;
    /**
     * @return string|int
     * @param object $media
     */
    public function getMediaItemID($media);
    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getTitle($mediaObjectOrID) : ?string;
    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getCaption($mediaObjectOrID) : ?string;
    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getAltText($mediaObjectOrID) : ?string;
    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getDescription($mediaObjectOrID) : ?string;
    /**
     * @param string|int|object $mediaObjectOrID
     * @param bool $gmt
     */
    public function getDate($mediaObjectOrID, $gmt = \false) : ?string;
    /**
     * @param string|int|object $mediaObjectOrID
     * @param bool $gmt
     */
    public function getModified($mediaObjectOrID, $gmt = \false) : ?string;
    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getMimeType($mediaObjectOrID) : ?string;
}
