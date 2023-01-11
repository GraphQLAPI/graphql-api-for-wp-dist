<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\Constants;

class MutationInputProperties
{
    public const INPUT = 'input';
    /**
     * Call it "id" instead of "customPostID" because this input
     * will be exposed in the GraphQL schema, for any CPT
     * (post, event, etc)
     */
    public const CUSTOMPOST_ID = 'id';
    public const CATEGORY_IDS = 'categoryIDs';
    public const APPEND = 'append';
}
