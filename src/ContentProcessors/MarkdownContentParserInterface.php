<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

interface MarkdownContentParserInterface extends ContentParserInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     * @param string $markdownContent
     */
    public function convertMarkdownToHTML($markdownContent): string;
}
