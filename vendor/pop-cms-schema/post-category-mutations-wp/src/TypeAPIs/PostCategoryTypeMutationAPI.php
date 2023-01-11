<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutationsWP\TypeAPIs;

use PoPCMSSchema\PostCategoryMutations\TypeAPIs\PostCategoryTypeMutationAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostCategoryTypeMutationAPI implements PostCategoryTypeMutationAPIInterface
{
    /**
     * @param array<string|int> $categoryIDs
     * @param int|string $postID
     * @param bool $append
     */
    public function setCategories($postID, $categoryIDs, $append = false): void
    {
        \wp_set_post_terms((int)$postID, $categoryIDs, 'category', $append);
    }
}
