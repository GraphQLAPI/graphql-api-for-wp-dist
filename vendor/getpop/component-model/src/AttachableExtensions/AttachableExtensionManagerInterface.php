<?php

declare (strict_types=1);
namespace PoP\ComponentModel\AttachableExtensions;

interface AttachableExtensionManagerInterface
{
    /**
     * @param string $attachableClass Class or "*" to represent _any_ class
     * @param string $group
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface $attachableExtension
     */
    public function attachExtensionToClass($attachableClass, $group, $attachableExtension) : void;
    /**
     * @return AttachableExtensionInterface[]
     * @param string $attachableClass
     * @param string $group
     */
    public function getAttachedExtensions($attachableClass, $group) : array;
}
