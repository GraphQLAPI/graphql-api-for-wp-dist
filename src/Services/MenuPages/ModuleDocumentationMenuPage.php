<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\Constants\RequestParams;
use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\Exception\ContentNotExistsException;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use PoP\Root\App;

/**
 * Module Documentation menu page
 */
class ModuleDocumentationMenuPage extends AbstractDocsMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface|null
     */
    private $moduleRegistry;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage|null
     */
    private $modulesMenuPage;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface $moduleRegistry
     */
    final public function setModuleRegistry($moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry = $this->moduleRegistry ?? $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage $modulesMenuPage
     */
    final public function setModulesMenuPage($modulesMenuPage): void
    {
        $this->modulesMenuPage = $modulesMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        /** @var ModulesMenuPage */
        return $this->modulesMenuPage = $this->modulesMenuPage ?? $this->instanceManager->getInstance(ModulesMenuPage::class);
    }

    public function getMenuPageSlug(): string
    {
        return $this->getModulesMenuPage()->getMenuPageSlug();
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return $this->getMenuPageHelper()->isDocumentationScreen() && parent::isCurrentScreen();
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
        /** @var array<string,mixed> */
        $result = [];
        parse_str(App::server('REQUEST_URI'), $result);
        /** @var string */
        $requestedModule = $result[RequestParams::MODULE];
        $module = urldecode($requestedModule);
        try {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        } catch (ContentNotExistsException $exception) {
            return sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, module \'%s\' does not exist', 'graphql-api'),
                    $module
                )
            );
        }
        $hasDocumentation = $moduleResolver->hasDocumentation($module);
        $documentation = '';
        if ($hasDocumentation) {
            $documentation = $moduleResolver->getDocumentation($module);
        }
        if (!$hasDocumentation || $documentation === null) {
            return sprintf(
                '<p>%s</p>',
                sprintf(
                    \__('Oops, module \'%s\' has no documentation', 'graphql-api'),
                    $moduleResolver->getName($module)
                )
            );
        }
        return $documentation;
    }
}
