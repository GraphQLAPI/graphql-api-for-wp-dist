<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\ConditionalOnModule\Users\TypeAPIs;

interface UserMediaTypeAPIInterface
{
    /**
     * @param string|int|object $mediaObjectOrID
     * @return string|int|null
     */
    public function getMediaAuthorID($mediaObjectOrID);
}
