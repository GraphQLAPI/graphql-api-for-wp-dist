<?php

declare (strict_types=1);
namespace PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs;

interface MediaTypeAPIInterface
{
    /**
     * @param string|int|object $mediaObjectOrID
     * @return string|int|null
     */
    public function getMediaAuthorId($mediaObjectOrID);
}
