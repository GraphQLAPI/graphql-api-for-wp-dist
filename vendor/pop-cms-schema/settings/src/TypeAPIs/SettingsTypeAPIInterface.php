<?php

declare (strict_types=1);
namespace PoPCMSSchema\Settings\TypeAPIs;

use PoPCMSSchema\Settings\Exception\OptionNotAllowedException;
interface SettingsTypeAPIInterface
{
    /**
     * @param array<string,mixed> $options
     * @throws OptionNotAllowedException When the option does not exist, or is not in the allowlist
     * @return mixed
     * @param string $name
     */
    public function getOption($name, $options = []);
    /**
     * @param string $key
     */
    public function validateIsOptionAllowed($key) : bool;
    /**
     * @return string[]
     */
    public function getAllowOrDenyOptionEntries() : array;
    public function getAllowOrDenyOptionBehavior() : string;
}
