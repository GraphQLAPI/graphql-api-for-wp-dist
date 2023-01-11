<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaWP\ConditionalOnModule\Users\TypeAPIs;

use PoPCMSSchema\Media\ConditionalOnModule\Users\TypeAPIs\UserMediaTypeAPIInterface;
use WP_Post;

use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserMediaTypeAPI implements UserMediaTypeAPIInterface
{
    /**
     * @param string|int|object $mediaObjectOrID
     * @return string|int|null
     */
    public function getMediaAuthorID($mediaObjectOrID)
    {
        if (is_object($mediaObjectOrID)) {
            $media = $mediaObjectOrID;
        } else {
            $media = get_post((int)$mediaObjectOrID);
        }
        /** @var WP_Post $media */
        return $media->post_author;
    }
}
