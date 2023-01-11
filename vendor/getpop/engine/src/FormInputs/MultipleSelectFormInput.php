<?php

declare (strict_types=1);
namespace PoP\Engine\FormInputs;

class MultipleSelectFormInput extends \PoP\Engine\FormInputs\SelectFormInput
{
    public function isMultiple() : bool
    {
        return \true;
    }
}
