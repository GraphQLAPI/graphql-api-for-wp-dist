<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;

class TopMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper|null
     */
    private $menuPageHelper;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface|null
     */
    private $moduleRegistry;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface|null
     */
    private $userAuthorization;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage|null
     */
    private $graphiQLMenuPage;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage|null
     */
    private $graphQLVoyagerMenuPage;

    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper $menuPageHelper
     */
    final public function setMenuPageHelper($menuPageHelper): void
    {
        $this->menuPageHelper = $menuPageHelper;
    }
    final protected function getMenuPageHelper(): MenuPageHelper
    {
        /** @var MenuPageHelper */
        return $this->menuPageHelper = $this->menuPageHelper ?? $this->instanceManager->getInstance(MenuPageHelper::class);
    }
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
     * @param \GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface $userAuthorization
     */
    final public function setUserAuthorization($userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization = $this->userAuthorization ?? $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage $graphiQLMenuPage
     */
    final public function setGraphiQLMenuPage($graphiQLMenuPage): void
    {
        $this->graphiQLMenuPage = $graphiQLMenuPage;
    }
    final protected function getGraphiQLMenuPage(): GraphiQLMenuPage
    {
        /** @var GraphiQLMenuPage */
        return $this->graphiQLMenuPage = $this->graphiQLMenuPage ?? $this->instanceManager->getInstance(GraphiQLMenuPage::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage
     */
    final public function setGraphQLVoyagerMenuPage($graphQLVoyagerMenuPage): void
    {
        $this->graphQLVoyagerMenuPage = $graphQLVoyagerMenuPage;
    }
    final protected function getGraphQLVoyagerMenuPage(): GraphQLVoyagerMenuPage
    {
        /** @var GraphQLVoyagerMenuPage */
        return $this->graphQLVoyagerMenuPage = $this->graphQLVoyagerMenuPage ?? $this->instanceManager->getInstance(GraphQLVoyagerMenuPage::class);
    }

    /**
     * Before adding the menus for the CPTs
     */
    protected function getPriority(): int
    {
        return 6;
    }

    public function addMenuPages(): void
    {
        $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('GraphiQL', 'graphql-api'),
                __('GraphiQL', 'graphql-api'),
                $schemaEditorAccessCapability,
                $this->getMenuName(),
                [$this->getGraphiQLMenuPage(), 'print']
            )
        ) {
            $this->getGraphiQLMenuPage()->setHookName($hookName);
        }

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('Interactive Schema', 'graphql-api'),
                __('Interactive Schema', 'graphql-api'),
                $schemaEditorAccessCapability,
                $this->getGraphQLVoyagerMenuPage()->getScreenID(),
                [$this->getGraphQLVoyagerMenuPage(), 'print']
            )
        ) {
            $this->getGraphQLVoyagerMenuPage()->setHookName($hookName);
        }
    }
}
