<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostCategoryMutations\TypeAPIs;

interface CustomPostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categoryIDs
     * @param int|string $postID
     */
    public function setCategories($postID, array $categoryIDs, bool $append = \false) : void;
}
