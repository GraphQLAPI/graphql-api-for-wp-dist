<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Constants;

class HookNames
{
    public const HOOK_QUERYABLE_CUSTOMPOST_TYPES = __CLASS__ . ':queryable-custompost-types';
    public const HOOK_REJECTED_QUERYABLE_CUSTOMPOST_TYPES = __CLASS__ . ':rejected-queryable-custompost-types';
    public const HOOK_QUERYABLE_TAG_TAXONOMIES = __CLASS__ . ':queryable-tag-taxonomies';
    public const HOOK_REJECTED_QUERYABLE_TAG_TAXONOMIES = __CLASS__ . ':rejected-queryable-tag-taxonomies';
    public const HOOK_QUERYABLE_CATEGORY_TAXONOMIES = __CLASS__ . ':queryable-category-taxonomies';
    public const HOOK_REJECTED_QUERYABLE_CATEGORY_TAXONOMIES = __CLASS__ . ':rejected-queryable-category-taxonomies';
}
