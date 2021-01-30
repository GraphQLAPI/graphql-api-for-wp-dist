<?php

namespace PoP\Engine;

class GD_FormInput_MultiSelect extends \PoP\Engine\GD_FormInput_Select
{
    public function isMultiple()
    {
        return \true;
    }
}
