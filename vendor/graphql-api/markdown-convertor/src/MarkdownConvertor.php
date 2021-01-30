<?php

declare (strict_types=1);
namespace GraphQLAPI\MarkdownConvertor;

use PrefixedByPoP\Parsedown;
class MarkdownConvertor implements \GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     */
    public function convertMarkdownToHTML(string $markdownContent) : string
    {
        return (new \PrefixedByPoP\Parsedown())->text($markdownContent);
    }
}
