<?php

declare (strict_types=1);
namespace PoPSchema\CommentMeta\TypeAPIs;

use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPSchema\SchemaCommons\Facades\Services\AllowOrDenySettingsServiceFacade;
abstract class AbstractCommentMetaTypeAPI implements CommentMetaTypeAPIInterface
{
    /**
     * @param string|int $commentID
     * @return mixed
     */
    public final function getCommentMeta($commentID, string $key, bool $single = \false)
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getCommentMetaEntries();
        $behavior = ComponentConfiguration::getCommentMetaBehavior();
        $allowOrDenySettingsService = AllowOrDenySettingsServiceFacade::getInstance();
        if (!$allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetCommentMeta($commentID, $key, $single);
    }
    /**
     * @param string|int $commentID
     * @return mixed
     */
    protected abstract function doGetCommentMeta($commentID, string $key, bool $single = \false);
}
