<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

interface ContentParserInterface
{
    /**
     * Inject the dir where to look for the documentation.
     * If null, it uses the default value from the main plugin.
     * @param string|null $baseDir
     */
    public function setBaseDir($baseDir = null): void;

    /**
     * Inject the URL where to look for the documentation.
     * If null, it uses the default value from the main plugin.
     * @param string|null $baseDir
     */
    public function setBaseURL($baseDir = null): void;

    /**
     * Parse the file's Markdown into HTML Content
     *
     * @param string $relativePathDir Dir relative to the docs/en/ folder
     * @param array<string,mixed> $options
     * @param string $filename
     * @param string $extension
     */
    public function getContent(
        $filename,
        $extension,
        $relativePathDir = '',
        $options = []
    ): string;

    /**
     * Default language for documentation
     */
    public function getDefaultDocsLanguage(): string;
}
