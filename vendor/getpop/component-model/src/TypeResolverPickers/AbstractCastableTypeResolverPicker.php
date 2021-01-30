<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;
use PoP\ComponentModel\TypeResolverPickers\CastableTypeResolverPickerInterface;
abstract class AbstractCastableTypeResolverPicker extends \PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker implements \PoP\ComponentModel\TypeResolverPickers\CastableTypeResolverPickerInterface
{
    /**
     * @param object $resultItem
     */
    public function cast($resultItem)
    {
        return $resultItem;
    }
}
