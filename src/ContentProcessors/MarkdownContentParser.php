<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;

class MarkdownContentParser extends AbstractContentParser implements MarkdownContentParserInterface
{
    /**
     * @var \GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface
     */
    protected $markdownConvertorInterface;
    public function __construct(RequestHelperServiceInterface $requestHelperService, MarkdownConvertorInterface $markdownConvertorInterface, ?string $baseDir = null, ?string $baseURL = null)
    {
        $this->markdownConvertorInterface = $markdownConvertorInterface;
        parent::__construct($requestHelperService, $baseDir, $baseURL);
    }
    /**
     * Parse the file's Markdown into HTML Content
     */
    protected function getHTMLContent(string $fileContent): string
    {
        return $this->convertMarkdownToHTML($fileContent);
    }

    /**
     * Parse the file's Markdown into HTML Content
     */
    public function convertMarkdownToHTML(string $markdownContent): string
    {
        return $this->markdownConvertorInterface->convertMarkdownToHTML($markdownContent);
    }
}
