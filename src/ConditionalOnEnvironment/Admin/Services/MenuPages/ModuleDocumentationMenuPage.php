<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\General\RequestParams;
use InvalidArgumentException;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Module Documentation menu page
 */
class ModuleDocumentationMenuPage extends AbstractDocsMenuPage
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Helpers\MenuPageHelper
     */
    protected $menuPageHelper;

    function __construct(MenuPageHelper $menuPageHelper)
    {
        $this->menuPageHelper = $menuPageHelper;
    }

    public function getMenuPageSlug(): string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var ModulesMenuPage
         */
        $modulesMenuPage = $instanceManager->getInstance(ModulesMenuPage::class);
        return $modulesMenuPage->getMenuPageSlug();
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return $this->menuPageHelper->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function openInModalWindow(): bool
    {
        return true;
    }

    protected function useTabpanelForContent(): bool
    {
        return true;
    }

    protected function getContentToPrint(): string
    {
        // This is crazy: passing ?module=Foo\Bar\module,
        // and then doing $_GET['module'], returns "Foo\\Bar\\module"
        // So parse the URL to extract the "module" param
        $vars = [];
        parse_str($_SERVER['REQUEST_URI'], $vars);
        $module = urldecode($vars[RequestParams::MODULE]);
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        try {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
        } catch (InvalidArgumentException $e) {
            return sprintf('<p>%s</p>', sprintf(\__('Oops, module \'%s\' does not exist', 'graphql-api'), $module));
        }
        $hasDocumentation = $moduleResolver->hasDocumentation($module);
        $documentation = '';
        if ($hasDocumentation) {
            $documentation = $moduleResolver->getDocumentation($module);
        }
        if (!$hasDocumentation || $documentation === null) {
            return sprintf('<p>%s</p>', sprintf(\__('Oops, module \'%s\' has no documentation', 'graphql-api'), $moduleResolver->getName($module)));
        }
        return $documentation;
    }
}
