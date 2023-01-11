<?php

declare (strict_types=1);
namespace GraphQLAPI\MarkdownConvertor;

interface MarkdownConvertorInterface
{
    /**
     * Parse the file's Markdown into HTML Content
     * @param string $markdownContent
     */
    public function convertMarkdownToHTML($markdownContent) : string;
}
