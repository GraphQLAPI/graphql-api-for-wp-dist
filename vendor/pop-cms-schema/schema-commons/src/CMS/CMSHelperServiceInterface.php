<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\CMS;

interface CMSHelperServiceInterface
{
    /**
     * Get the path from the URL if it starts with the home URL, of `null` otherwise
     * @param string $url
     */
    public function getLocalURLPath($url) : ?string;
}
