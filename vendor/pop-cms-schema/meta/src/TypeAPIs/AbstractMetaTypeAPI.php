<?php

declare (strict_types=1);
namespace PoPCMSSchema\Meta\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
abstract class AbstractMetaTypeAPI implements \PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface|null
     */
    private $allowOrDenySettingsService;
    /**
     * @param \PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface $allowOrDenySettingsService
     */
    public final function setAllowOrDenySettingsService($allowOrDenySettingsService) : void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }
    protected final function getAllowOrDenySettingsService() : AllowOrDenySettingsServiceInterface
    {
        /** @var AllowOrDenySettingsServiceInterface */
        return $this->allowOrDenySettingsService = $this->allowOrDenySettingsService ?? $this->instanceManager->getInstance(AllowOrDenySettingsServiceInterface::class);
    }
    /**
     * @param string $key
     */
    public final function validateIsMetaKeyAllowed($key) : bool
    {
        return $this->getAllowOrDenySettingsService()->isEntryAllowed($key, $this->getAllowOrDenyMetaEntries(), $this->getAllowOrDenyMetaBehavior());
    }
    /**
     * If the allow/denylist validation fails, throw an exception.
     *
     * @throws MetaKeyNotAllowedException
     * @param string $key
     */
    protected final function assertIsMetaKeyAllowed($key) : void
    {
        if (!$this->validateIsMetaKeyAllowed($key)) {
            throw new MetaKeyNotAllowedException(\sprintf($this->__('There is no meta with key \'%s\'', 'commentmeta'), $key));
        }
    }
}
