<?php

declare (strict_types=1);
namespace PoP\ComponentModel\AttachableExtensions;

class AttachableExtensionManager implements \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface
{
    /**
     * @var array<string, array>
     */
    protected $extensionClasses = [];
    public function setExtensionClass(string $attachableClass, string $group, string $extensionClass, int $priority = 10) : void
    {
        $this->extensionClasses[$attachableClass][$group][$extensionClass] = $priority;
    }
    public function getExtensionClasses(string $attachableClass, string $group) : array
    {
        return $this->extensionClasses[$attachableClass][$group] ?? [];
    }
}
