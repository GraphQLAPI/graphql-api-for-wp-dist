<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;

/**
 * Release notes menu page
 */
class ReleaseNotesAboutMenuPage extends AbstractDocAboutMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    /**
     * @var \GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage|null
     */
    private $aboutMenuPage;

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

    public function getMenuPageSlug(): string
    {
        return $this->getAboutMenuPage()->getMenuPageSlug();
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return $this->getMenuPageHelper()->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function getRelativePathDir(): string
    {
        return 'release-notes';
    }
}
