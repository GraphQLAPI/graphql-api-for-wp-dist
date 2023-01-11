<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;
class UsernameOrUsernamesFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey() : string
    {
        return 'username';
    }
    /**
     * @param mixed $value
     * @return mixed
     */
    protected function getValue($value)
    {
        return \is_array($value) ? $value : [$value];
    }
}
