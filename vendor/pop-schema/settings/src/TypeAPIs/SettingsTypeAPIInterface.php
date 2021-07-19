<?php

declare (strict_types=1);
namespace PoPSchema\Settings\TypeAPIs;

interface SettingsTypeAPIInterface
{
    /**
     * @return mixed
     */
    public function getOption(string $name);
}
