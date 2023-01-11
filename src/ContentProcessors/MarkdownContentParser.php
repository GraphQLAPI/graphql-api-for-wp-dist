<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface;

class MarkdownContentParser extends AbstractContentParser implements MarkdownContentParserInterface
{
    /**
     * @var \GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface|null
     */
    private $markdownConvertor;

    /**
     * @param \GraphQLAPI\MarkdownConvertor\MarkdownConvertorInterface $markdownConvertor
     */
    final public function setMarkdownConvertor($markdownConvertor): void
    {
        $this->markdownConvertor = $markdownConvertor;
    }
    final protected function getMarkdownConvertor(): MarkdownConvertorInterface
    {
        /** @var MarkdownConvertorInterface */
        return $this->markdownConvertor = $this->markdownConvertor ?? $this->instanceManager->getInstance(MarkdownConvertorInterface::class);
    }

    /**
     * Parse the file's Markdown into HTML Content
     * @param string $fileContent
     */
    protected function getHTMLContent($fileContent): string
    {
        return $this->convertMarkdownToHTML($fileContent);
    }

    /**
     * Parse the file's Markdown into HTML Content
     * @param string $markdownContent
     */
    public function convertMarkdownToHTML($markdownContent): string
    {
        return $this->getMarkdownConvertor()->convertMarkdownToHTML($markdownContent);
    }
}
