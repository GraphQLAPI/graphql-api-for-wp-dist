<?php

declare (strict_types=1);
namespace PoP\ComponentModel\AttachableExtensions;

use PoP\Root\Services\ServiceInterface;
class AttachExtensionService implements \PoP\ComponentModel\AttachableExtensions\AttachExtensionServiceInterface
{
    /**
     * @var array<string,array<string,AttachableExtensionInterface[]>>
     */
    protected $classGroups = [];
    /**
     * @param string $event
     * @param string $group
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface $extension
     */
    public function enqueueExtension($event, $group, $extension) : void
    {
        $this->classGroups[$event][$group][] = $extension;
    }
    /**
     * @param string $event
     */
    public function attachExtensions($event) : void
    {
        foreach ($this->classGroups[$event] ?? [] as $group => $extensions) {
            // Only attach the enabled thervices
            $extensions = \array_filter($extensions, function (ServiceInterface $extension) {
                return $extension->isServiceEnabled();
            });
            foreach ($extensions as $extension) {
                $extension->attach($group);
            }
        }
    }
}
