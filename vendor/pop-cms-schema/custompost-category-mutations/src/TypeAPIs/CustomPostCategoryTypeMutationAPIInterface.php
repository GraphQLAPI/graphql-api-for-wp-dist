<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs;

interface CustomPostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categoryIDs
     * @param int|string $postID
     * @param bool $append
     */
    public function setCategories($postID, $categoryIDs, $append = \false) : void;
}
