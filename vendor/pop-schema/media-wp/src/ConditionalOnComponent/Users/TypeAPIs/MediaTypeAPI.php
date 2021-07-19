<?php

declare(strict_types=1);

namespace PoPSchema\MediaWP\ConditionalOnComponent\Users\TypeAPIs;

use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\MediaTypeAPIInterface;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class MediaTypeAPI implements MediaTypeAPIInterface
{
    /**
     * @param string|int|object $mediaObjectOrID
     * @return string|int|null
     */
    public function getMediaAuthorId($mediaObjectOrID)
    {
        if (is_object($mediaObjectOrID)) {
            $media = $mediaObjectOrID;
        } else {
            $media = get_post($mediaObjectOrID);
        }
        return $media->post_author;
    }
}
