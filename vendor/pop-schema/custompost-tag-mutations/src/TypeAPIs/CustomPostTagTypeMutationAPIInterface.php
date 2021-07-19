<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostTagMutations\TypeAPIs;

interface CustomPostTagTypeMutationAPIInterface
{
    /**
     * @param string[] $tags
     * @param int|string $postID
     */
    public function setTags($postID, array $tags, bool $append = \false) : void;
}
