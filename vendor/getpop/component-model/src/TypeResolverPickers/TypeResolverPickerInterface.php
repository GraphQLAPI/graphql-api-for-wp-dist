<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface;
interface TypeResolverPickerInterface extends AttachableExtensionInterface
{
    public function getTypeResolverClass() : string;
    /**
     * @param string|int $resultItemID
     */
    public function isIDOfType($resultItemID) : bool;
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool;
}
