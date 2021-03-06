<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\ContentProcessors\ContentParserOptions;
use GraphQLAPI\GraphQLAPI\Facades\ContentProcessors\MarkdownContentParserFacade;
use InvalidArgumentException;

/**
 * About menu page
 */
class AboutMenuPage extends AbstractDocsMenuPage
{
    use OpenInModalTriggerMenuPageTrait;

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
        return 'about';
    }

    protected function useTabpanelForContent(): bool
    {
        return true;
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return !$this->menuPageHelper->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function getContentToPrint(): string
    {
        $markdownContentParser = MarkdownContentParserFacade::getInstance();
        try {
            return $markdownContentParser->getContent('about.md', '', [ContentParserOptions::TAB_CONTENT => true]);
        } catch (InvalidArgumentException $e) {
            return sprintf('<p>%s</p>', \__('Oops, there was a problem loading the page', 'graphql-api'));
        }
    }

    /**
     * Enqueue the required assets
     */
    protected function enqueueAssets(): void
    {
        parent::enqueueAssets();

        $this->enqueueModalTriggerAssets();
    }
}
