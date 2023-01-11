<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserMeta\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPCMSSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPCMSSchema\UserMeta\Module;
use PoPCMSSchema\UserMeta\ModuleConfiguration;
abstract class AbstractUserMetaTypeAPI extends AbstractMetaTypeAPI implements \PoPCMSSchema\UserMeta\TypeAPIs\UserMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws MetaKeyNotAllowedException
     * @param string|int|object $userObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    public final function getUserMeta($userObjectOrID, $key, $single = \false, $options = [])
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetUserMeta($userObjectOrID, $key, $single);
    }
    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getUserMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior() : string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getUserMetaBehavior();
    }
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     * @param string|int|object $userObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    protected abstract function doGetUserMeta($userObjectOrID, $key, $single = \false);
}
