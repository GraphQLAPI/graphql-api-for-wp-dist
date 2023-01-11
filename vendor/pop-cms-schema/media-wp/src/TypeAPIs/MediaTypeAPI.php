<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPCMSSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use WP_Post;

use function get_post;
use function wp_get_attachment_image_src;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class MediaTypeAPI extends AbstractCustomPostTypeAPI implements MediaTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * Indicates if the passed object is of type Media
     * @param object $object
     */
    public function isInstanceOfMediaType($object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type === 'attachment';
    }

    /**
     * @param string|int|object $mediaItemObjectOrID
     */
    public function getMediaItemSrc($mediaItemObjectOrID): ?string
    {
        if (is_object($mediaItemObjectOrID)) {
            /** @var WP_Post */
            $mediaItemObject = $mediaItemObjectOrID;
            $mediaItemID = $mediaItemObject->ID;
        } else {
            $mediaItemID = $mediaItemObjectOrID;
        }
        $url = \wp_get_attachment_url((int)$mediaItemID);
        if ($url === false) {
            return null;
        }
        return $url;
    }

    /**
     * @param string|int|object $mediaItemObjectOrID
     */
    public function getMediaItemSrcPath($mediaItemObjectOrID): ?string
    {
        $src = $this->getMediaItemSrc($mediaItemObjectOrID);
        if ($src === null) {
            return null;
        }
        return $this->getCMSHelperService()->getLocalURLPath($src);
    }

    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSrc($mediaItemObjectOrID, $size = null): ?string
    {
        $img = $this->getImageProperties($mediaItemObjectOrID, $size);
        if ($img === null) {
            return null;
        }
        return $img['src'];
    }

    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSrcPath($mediaItemObjectOrID, $size = null): ?string
    {
        $src = $this->getImageSrc($mediaItemObjectOrID, $size);
        if ($src === null) {
            return null;
        }
        return $this->getCMSHelperService()->getLocalURLPath($src);
    }

    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSrcSet($mediaItemObjectOrID, $size = null): ?string
    {
        if (is_object($mediaItemObjectOrID)) {
            /** @var WP_Post */
            $mediaItemObject = $mediaItemObjectOrID;
            $mediaItemID = $mediaItemObject->ID;
        } else {
            $mediaItemID = $mediaItemObjectOrID;
        }
        $srcSet = \wp_get_attachment_image_srcset((int)$mediaItemID, $size ?? '');
        if ($srcSet === false) {
            return null;
        }
        return $srcSet;
    }

    /**
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageSizes($mediaItemObjectOrID, $size = null): ?string
    {
        $imageProperties = $this->getImageProperties($mediaItemObjectOrID, $size);
        if ($imageProperties === null) {
            return null;
        }
        if (is_object($mediaItemObjectOrID)) {
            /** @var WP_Post */
            $mediaItemObject = $mediaItemObjectOrID;
            $mediaItemID = $mediaItemObject->ID;
        } else {
            $mediaItemID = $mediaItemObjectOrID;
        }
        /** @var int[] */
        $imageSize = [(int)$imageProperties['width'], (int)$imageProperties['height']];
        $sizes = \wp_calculate_image_sizes($imageSize, $imageProperties['src'], null, (int)$mediaItemID);
        if ($sizes === false) {
            return null;
        }
        return $sizes;
    }

    /**
     * @return array{src: string, width: ?int, height: ?int}
     * @param string|int|object $mediaItemObjectOrID
     * @param string|null $size
     */
    public function getImageProperties($mediaItemObjectOrID, $size = null): ?array
    {
        if (is_object($mediaItemObjectOrID)) {
            /** @var WP_Post */
            $mediaItemObject = $mediaItemObjectOrID;
            $mediaItemID = $mediaItemObject->ID;
        } else {
            $mediaItemID = $mediaItemObjectOrID;
        }
        $img = wp_get_attachment_image_src((int)$mediaItemID, $size ?? '');
        if ($img === false) {
            return null;
        }
        return [
            'src' => $img[0],
            'width' => $img[1],
            'height' => $img[2]
        ];
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function convertCustomPostsQuery($query, $options = []): array
    {
        $query = parent::convertCustomPostsQuery($query, $options);

        $query = $this->convertMediaQuery($query, $options);

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function getCustomPostQueryDefaults(): array
    {
        // For media, must remove the status or the query doesn't work
        $queryDefaults = parent::getCustomPostQueryDefaults();
        unset($queryDefaults['status']);
        return $queryDefaults;
    }

    /**
     * Query args that must always be in the query
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return array_merge(
            parent::getCustomPostQueryRequiredArgs(),
            [
                'custompost-types' => ['attachment'],
            ]
        );
    }

    /**
     * Get the media item with provided ID or, if it doesn't exist, null
     * @param int|string $id
     */
    public function getMediaItem($id)
    {
        $post = get_post((int)$id);
        if ($post === null || $post->post_type !== 'attachment') {
            return null;
        }
        return $post;
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItems($query, $options = []): array
    {
        return $this->getCustomPosts($query, $options);
    }

    /**
     * @param int|string $id
     */
    public function mediaItemExists($id): bool
    {
        return $this->getMediaItem($id) !== null;
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getMediaItemCount($query = [], $options = []): int
    {
        return $this->getCustomPostCount($query, $options);
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertMediaQuery($query, $options = []): array
    {
        if (isset($query['mime-types'])) {
            // Transform from array to string
            $query['post_mime_type'] = implode(',', $query['mime-types']);
            unset($query['mime-types']);
        }

        return $query;
    }

    /**
     * @return string|int
     * @param object $mediaItem
     */
    public function getMediaItemID($mediaItem)
    {
        /** @var WP_Post $mediaItem */
        return $mediaItem->ID;
    }

    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getTitle($mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_title;
    }

    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getCaption($mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_excerpt;
    }

    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getAltText($mediaObjectOrID): ?string
    {
        $mediaItemID = $this->getCustomPostID($mediaObjectOrID);
        return get_post_meta($mediaItemID, '_wp_attachment_image_alt', true);
    }

    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getDescription($mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_content;
    }

    /**
     * @param string|int|object $mediaObjectOrID
     * @param bool $gmt
     */
    public function getDate($mediaObjectOrID, $gmt = false): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $gmt ? $mediaItem->post_date_gmt : $mediaItem->post_date;
    }

    /**
     * @param string|int|object $mediaObjectOrID
     * @param bool $gmt
     */
    public function getModified($mediaObjectOrID, $gmt = false): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $gmt ? $mediaItem->post_modified_gmt : $mediaItem->post_modified;
    }

    /**
     * @param string|int|object $mediaObjectOrID
     */
    public function getMimeType($mediaObjectOrID): ?string
    {
        $mediaItem = $this->getCustomPostObject($mediaObjectOrID);
        if ($mediaItem === null) {
            return null;
        }
        /** @var WP_Post $mediaItem */
        return $mediaItem->post_mime_type;
    }
}
