<?php

declare(strict_types=1);

namespace PoPCMSSchema\SettingsWP\TypeAPIs;

use PoPCMSSchema\Settings\TypeAPIs\AbstractSettingsTypeAPI;

class SettingsTypeAPI extends AbstractSettingsTypeAPI
{
    /**
     * @return mixed
     * @param string $name
     */
    protected function doGetOption($name)
    {
        return \get_option($name);
    }
}
