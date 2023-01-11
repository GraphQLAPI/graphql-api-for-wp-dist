<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMeta\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\CommentMeta\Module;
use PoPCMSSchema\CommentMeta\ModuleConfiguration;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPCMSSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
abstract class AbstractCommentMetaTypeAPI extends AbstractMetaTypeAPI implements \PoPCMSSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws MetaKeyNotAllowedException
     * @param string|int|object $commentObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    public final function getCommentMeta($commentObjectOrID, $key, $single = \false, $options = [])
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetCommentMeta($commentObjectOrID, $key, $single);
    }
    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCommentMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior() : string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCommentMetaBehavior();
    }
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     * @param string|int|object $commentObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    protected abstract function doGetCommentMeta($commentObjectOrID, $key, $single = \false);
}
