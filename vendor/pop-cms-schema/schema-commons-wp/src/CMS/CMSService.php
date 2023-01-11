<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommonsWP\CMS;

use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;

use function get_option;
use function get_site_url;
use function home_url;

class CMSService implements CMSServiceInterface
{
    /**
     * @param mixed $default
     * @return mixed
     * @param string $option
     */
    public function getOption($option, $default = false)
    {
        return get_option($option, $default);
    }

    /**
     * @param string $path
     */
    public function getHomeURL($path = ''): string
    {
        return home_url($path);
    }

    public function getSiteURL(): string
    {
        return get_site_url();
    }
}
