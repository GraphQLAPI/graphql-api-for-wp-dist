<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\FilterInputs;

use PoP\ComponentModel\FilterInputs\AbstractValueToQueryFilterInput;
class EmailOrEmailsFilterInput extends AbstractValueToQueryFilterInput
{
    protected function getQueryArgKey() : string
    {
        return 'emails';
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
