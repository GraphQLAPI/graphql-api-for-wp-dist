<?php

declare (strict_types=1);
namespace PoP\ComponentModel\AttachableExtensions;

use PoP\ComponentModel\Constants\ConfigurationValues;
class AttachableExtensionManager implements \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface
{
    /**
     * @var array<string,array<string,AttachableExtensionInterface[]>>
     */
    protected $attachableExtensions = [];
    /**
     * @param string $attachableClass Class or "*" to represent _any_ class
     * @param string $group
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface $attachableExtension
     */
    public function attachExtensionToClass($attachableClass, $group, $attachableExtension) : void
    {
        $this->attachableExtensions[$attachableClass][$group][] = $attachableExtension;
    }
    /**
     * @return AttachableExtensionInterface[]
     * @param string $attachableClass
     * @param string $group
     */
    public function getAttachedExtensions($attachableClass, $group) : array
    {
        return \array_merge($this->attachableExtensions[ConfigurationValues::ANY][$group] ?? [], $this->attachableExtensions[$attachableClass][$group] ?? []);
    }
}
