<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostTagMutations\TypeAPIs;

interface CustomPostTagTypeMutationAPIInterface
{
    /**
     * @param string[] $tags
     * @param int|string $postID
     * @param bool $append
     */
    public function setTags($postID, $tags, $append = \false) : void;
}
