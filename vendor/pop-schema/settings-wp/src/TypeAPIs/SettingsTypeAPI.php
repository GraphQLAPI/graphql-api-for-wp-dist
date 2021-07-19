<?php

declare(strict_types=1);

namespace PoPSchema\SettingsWP\TypeAPIs;

use PoPSchema\Settings\TypeAPIs\AbstractSettingsTypeAPI;

class SettingsTypeAPI extends AbstractSettingsTypeAPI
{
    /**
     * @return mixed
     */
    protected function doGetOption(string $name)
    {
        return \get_option($name);
    }
}
