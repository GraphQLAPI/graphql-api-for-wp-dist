<?php

declare (strict_types=1);
namespace GraphQLAPI\MarkdownConvertor;

use PrefixedByPoP\Michelf\MarkdownExtra;
/**
 * Markdown formatter provided by `michelf/php-markdown`
 *
 * @see https://michelf.ca/projects/php-markdown/extra/
 */
class MarkdownConvertor implements \GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     * @param string $markdownContent
     */
    public function convertMarkdownToHTML($markdownContent) : string
    {
        return MarkdownExtra::defaultTransform($markdownContent);
    }
}
