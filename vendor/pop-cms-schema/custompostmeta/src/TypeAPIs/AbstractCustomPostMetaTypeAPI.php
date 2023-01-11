<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMeta\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\CustomPostMeta\Module;
use PoPCMSSchema\CustomPostMeta\ModuleConfiguration;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPCMSSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
abstract class AbstractCustomPostMetaTypeAPI extends AbstractMetaTypeAPI implements \PoPCMSSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws MetaKeyNotAllowedException
     * @param string|int|object $customPostObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    public final function getCustomPostMeta($customPostObjectOrID, $key, $single = \false, $options = [])
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetCustomPostMeta($customPostObjectOrID, $key, $single);
    }
    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCustomPostMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior() : string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getCustomPostMetaBehavior();
    }
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     * @param string|int|object $customPostObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    protected abstract function doGetCustomPostMeta($customPostObjectOrID, $key, $single = \false);
}
