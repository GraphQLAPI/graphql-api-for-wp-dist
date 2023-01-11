<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;

class PluginManagementFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use PluginManagementFunctionalityModuleResolverTrait;

    public const GENERAL = Plugin::NAMESPACE . '\general';

    /**
     * Setting options
     */
    public const OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE = 'add-release-notes-admin-notice';
    public const OPTION_PRINT_SETTINGS_WITH_TABS = 'print-settings-with-tabs';

    /**
     * @var \GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface|null
     */
    private $markdownContentParser;

    /**
     * @param \GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface $markdownContentParser
     */
    final public function setMarkdownContentParser($markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser = $this->markdownContentParser ?? $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GENERAL,
        ];
    }

    /**
     * @param string $module
     */
    public function canBeDisabled($module): bool
    {
        switch ($module) {
            case self::GENERAL:
                return false;
            default:
                return parent::canBeDisabled($module);
        }
    }

    /**
     * @param string $module
     */
    public function isHidden($module): bool
    {
        switch ($module) {
            case self::GENERAL:
                return true;
            default:
                return parent::isHidden($module);
        }
    }

    /**
     * @param string $module
     */
    public function getName($module): string
    {
        switch ($module) {
            case self::GENERAL:
                return \__('General', 'graphql-api');
            default:
                return $module;
        }
    }

    /**
     * @param string $module
     */
    public function getDescription($module): string
    {
        switch ($module) {
            case self::GENERAL:
                return \__('General options for the plugin', 'graphql-api');
            default:
                return parent::getDescription($module);
        }
    }

    /**
     * Default value for an option set by the module
     * @return mixed
     * @param string $module
     * @param string $option
     */
    public function getSettingsDefaultValue($module, $option)
    {
        $defaultValues = [
            self::GENERAL => [
                self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE => true,
                self::OPTION_PRINT_SETTINGS_WITH_TABS => true,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     * @param string $module
     */
    public function getSettings($module): array
    {
        $moduleSettings = parent::getSettings($module);
        if ($module === self::GENERAL) {
            $option = self::OPTION_ADD_RELEASE_NOTES_ADMIN_NOTICE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Display admin notice with release notes?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Immediately after upgrading the plugin, show an admin notice with a link to the latest release notes?', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_PRINT_SETTINGS_WITH_TABS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Organize these settings under tabs?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Have all options in this Settings page be organized under tabs, one tab per module.<br/>After ticking the checkbox, must click on "Save Changes" to be applied.', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
