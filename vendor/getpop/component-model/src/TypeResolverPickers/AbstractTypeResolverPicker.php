<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
abstract class AbstractTypeResolverPicker implements \PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface
{
    use AttachableExtensionTrait;
    /**
     * @param string|int $resultItemID
     */
    public function isIDOfType($resultItemID) : bool
    {
        return \true;
    }
}
