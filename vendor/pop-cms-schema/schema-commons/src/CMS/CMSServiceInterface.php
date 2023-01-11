<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\CMS;

interface CMSServiceInterface
{
    /**
     * @param mixed $default
     * @return mixed
     * @param string $option
     */
    public function getOption($option, $default = \false);
    /**
     * @param string $path
     */
    public function getHomeURL($path = '') : string;
    public function getSiteURL() : string;
}
