<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\MenuPageInterface;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModuleDocumentationMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ReleaseNotesAboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration as GraphQLClientsForWPModuleConfiguration;
use PoP\Root\App;

class BottomMenuPageAttacher extends AbstractPluginMenuPageAttacher
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
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage|null
     */
    private $settingsMenuPage;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\ModuleDocumentationMenuPage|null
     */
    private $moduleDocumentationMenuPage;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage|null
     */
    private $modulesMenuPage;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\ReleaseNotesAboutMenuPage|null
     */
    private $releaseNotesAboutMenuPage;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage|null
     */
    private $aboutMenuPage;
    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy|null
     */
    private $graphQLEndpointCategoryTaxonomy;

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
     * @param \GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage $settingsMenuPage
     */
    final public function setSettingsMenuPage($settingsMenuPage): void
    {
        $this->settingsMenuPage = $settingsMenuPage;
    }
    final protected function getSettingsMenuPage(): SettingsMenuPage
    {
        /** @var SettingsMenuPage */
        return $this->settingsMenuPage = $this->settingsMenuPage ?? $this->instanceManager->getInstance(SettingsMenuPage::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\MenuPages\ModuleDocumentationMenuPage $moduleDocumentationMenuPage
     */
    final public function setModuleDocumentationMenuPage($moduleDocumentationMenuPage): void
    {
        $this->moduleDocumentationMenuPage = $moduleDocumentationMenuPage;
    }
    final protected function getModuleDocumentationMenuPage(): ModuleDocumentationMenuPage
    {
        /** @var ModuleDocumentationMenuPage */
        return $this->moduleDocumentationMenuPage = $this->moduleDocumentationMenuPage ?? $this->instanceManager->getInstance(ModuleDocumentationMenuPage::class);
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
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\MenuPages\ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage
     */
    final public function setReleaseNotesAboutMenuPage($releaseNotesAboutMenuPage): void
    {
        $this->releaseNotesAboutMenuPage = $releaseNotesAboutMenuPage;
    }
    final protected function getReleaseNotesAboutMenuPage(): ReleaseNotesAboutMenuPage
    {
        /** @var ReleaseNotesAboutMenuPage */
        return $this->releaseNotesAboutMenuPage = $this->releaseNotesAboutMenuPage ?? $this->instanceManager->getInstance(ReleaseNotesAboutMenuPage::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage $aboutMenuPage
     */
    final public function setAboutMenuPage($aboutMenuPage): void
    {
        $this->aboutMenuPage = $aboutMenuPage;
    }
    final protected function getAboutMenuPage(): AboutMenuPage
    {
        /** @var AboutMenuPage */
        return $this->aboutMenuPage = $this->aboutMenuPage ?? $this->instanceManager->getInstance(AboutMenuPage::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy
     */
    final public function setGraphQLEndpointCategoryTaxonomy($graphQLEndpointCategoryTaxonomy): void
    {
        $this->graphQLEndpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy;
    }
    final protected function getGraphQLEndpointCategoryTaxonomy(): GraphQLEndpointCategoryTaxonomy
    {
        /** @var GraphQLEndpointCategoryTaxonomy */
        return $this->graphQLEndpointCategoryTaxonomy = $this->graphQLEndpointCategoryTaxonomy ?? $this->instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);
    }

    /**
     * After adding the menus for the CPTs
     */
    protected function getPriority(): int
    {
        return 20;
    }

    public function addMenuPages(): void
    {
        global $submenu;
        $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();
        $menuName = $this->getMenuName();

        /**
         * Add the "Endpoint Categories" link to the menu.
         * Adding `"show_in_menu" => true` or `"show_in_menu" => "graphql_api"`
         * doesn't work, so we must use a hack.
         *
         * @see https://stackoverflow.com/questions/48632394/wordpress-add-custom-taxonomy-to-custom-menu
         */
        $graphQLEndpointCategoriesLabel = $this->getGraphQLEndpointCategoryTaxonomy()->getTaxonomyPluralNames(true);
        $graphQLEndpointCategoriesCustomPostTypes = $this->getGraphQLEndpointCategoryTaxonomy()->getCustomPostTypes();
        $graphQLEndpointCategoriesRelativePath = sprintf(
            'edit-tags.php?taxonomy=%s&post_type=%s',
            $this->getGraphQLEndpointCategoryTaxonomy()->getTaxonomy(),
            /**
             * The custom taxonomy has 2 CPTs associated to it:
             *
             * - Custom Endpoints
             * - Persisted Queries
             *
             * The "count" column shows the number from both of them,
             * but clicking on it should take to neither. That's why
             * param "post_type" points to the non-existing "both of them" CPT,
             * and so the link in "count" is removed.
             */
            implode(
                ',',
                $graphQLEndpointCategoriesCustomPostTypes
            )
        );

        /**
         * When clicking on "Endpoint Categories" it would highlight
         * the Posts menu. With this code, it highlights the GraphQL API menu.
         *
         * @see https://stackoverflow.com/a/66094349
         */
        \add_filter(
            'parent_file',
            function (string $parent_file) use ($graphQLEndpointCategoriesRelativePath) {
                global $plugin_page, $submenu_file, $taxonomy;
                if ($taxonomy === $this->getGraphQLEndpointCategoryTaxonomy()->getTaxonomy()) {
                    $plugin_page = $submenu_file = $graphQLEndpointCategoriesRelativePath;
                }
                return $parent_file;
            }
        );

        /**
         * Finally add the "Endpoint Categories" link to the menu.
         */
        \add_submenu_page($menuName, $graphQLEndpointCategoriesLabel, $graphQLEndpointCategoriesLabel, $schemaEditorAccessCapability, $graphQLEndpointCategoriesRelativePath);

        $modulesMenuPage = $this->getModuleMenuPage();
        /**
         * @var callable
         */
        $callable = [$modulesMenuPage, 'print'];
        if (
            $hookName = \add_submenu_page(
                $menuName,
                __('Modules', 'graphql-api'),
                __('Modules', 'graphql-api'),
                'manage_options',
                $modulesMenuPage->getScreenID(),
                $callable
            )
        ) {
            $modulesMenuPage->setHookName($hookName);
        }

        if (
            $hookName = \add_submenu_page(
                $menuName,
                __('Settings', 'graphql-api'),
                __('Settings', 'graphql-api'),
                'manage_options',
                $this->getSettingsMenuPage()->getScreenID(),
                [$this->getSettingsMenuPage(), 'print']
            )
        ) {
            $this->getSettingsMenuPage()->setHookName($hookName);
        }

        /** @var GraphQLClientsForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLClientsForWPModule::class)->getConfiguration();
        if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
            $clientPath = $moduleConfiguration->getGraphiQLClientEndpoint();
            $submenu[$menuName][] = [
                __('GraphiQL (public client)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }

        if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT)) {
            $clientPath = $moduleConfiguration->getVoyagerClientEndpoint();
            $submenu[$menuName][] = [
                __('Interactive Schema (public client)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }

        /**
         * Only show the About page when actually loading it
         * So it doesn't appear on the menu, but it's still available
         * to display the release notes on the modal window
         */
        $aboutMenuPage = $this->getReleaseNoteOrAboutMenuPage();
        if (App::query('page') === $aboutMenuPage->getScreenID()) {
            if (
                $hookName = \add_submenu_page(
                    $menuName,
                    __('About', 'graphql-api'),
                    __('About', 'graphql-api'),
                    'manage_options',
                    $aboutMenuPage->getScreenID(),
                    [$aboutMenuPage, 'print']
                )
            ) {
                $aboutMenuPage->setHookName($hookName);
            }
        }
    }

    /**
     * Either the Modules menu page, or the Module Documentation menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getModuleMenuPage(): MenuPageInterface
    {
        return
            $this->getMenuPageHelper()->isDocumentationScreen() ?
                $this->getModuleDocumentationMenuPage()
                : $this->getModulesMenuPage();
    }

    /**
     * Either the About menu page, or the Release Notes menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getReleaseNoteOrAboutMenuPage(): MenuPageInterface
    {
        return
            $this->getMenuPageHelper()->isDocumentationScreen() ?
                $this->getReleaseNotesAboutMenuPage()
                : $this->getAboutMenuPage();
    }
}
