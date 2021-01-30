<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolverPickers;

interface CastableTypeResolverPickerInterface
{
    /**
     * @param object $resultItem
     */
    public function cast($resultItem);
}
