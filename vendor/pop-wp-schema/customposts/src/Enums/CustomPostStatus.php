<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Enums;

class CustomPostStatus
{
    public const FUTURE = 'future';
    public const PRIVATE = 'private';
    public const INHERIT = 'inherit';
    /**
     * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
     *       Until then, this code is commented
     */
    //public final const AUTO_DRAFT = 'auto_draft';
}
