<?php

declare (strict_types=1);
namespace PoPCMSSchema\Settings\TypeAPIs;

use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\Settings\Module;
use PoPCMSSchema\Settings\ModuleConfiguration;
use PoPCMSSchema\Settings\Exception\OptionNotAllowedException;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
abstract class AbstractSettingsTypeAPI implements \PoPCMSSchema\Settings\TypeAPIs\SettingsTypeAPIInterface
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
     * If the allow/denylist validation fails, and passing option "assert-is-option-allowed",
     * then throw an exception.
     *
     * @param array<string,mixed> $options
     * @throws OptionNotAllowedException When the option name is not in the allowlist. Enabled by passing option "assert-is-option-allowed"
     * @return mixed
     * @param string $name
     */
    public final function getOption($name, $options = [])
    {
        if ($options['assert-is-option-allowed'] ?? null) {
            $this->assertIsOptionAllowed($name);
        }
        return $this->doGetOption($name);
    }
    /**
     * @return string[]
     */
    public function getAllowOrDenyOptionEntries() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getSettingsEntries();
    }
    public function getAllowOrDenyOptionBehavior() : string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getSettingsBehavior();
    }
    /**
     * @param string $name
     */
    public final function validateIsOptionAllowed($name) : bool
    {
        return $this->getAllowOrDenySettingsService()->isEntryAllowed($name, $this->getAllowOrDenyOptionEntries(), $this->getAllowOrDenyOptionBehavior());
    }
    /**
     * If the allow/denylist validation fails, throw an exception.
     *
     * @throws OptionNotAllowedException
     * @param string $name
     */
    protected final function assertIsOptionAllowed($name) : void
    {
        if (!$this->validateIsOptionAllowed($name)) {
            throw new OptionNotAllowedException(\sprintf($this->__('There is no option with name \'%s\'', 'settings'), $name));
        }
    }
    /**
     * If the name is non-existent, return `null`.
     * Otherwise, return the value.
     * @return mixed
     * @param string $name
     */
    protected abstract function doGetOption($name);
}
