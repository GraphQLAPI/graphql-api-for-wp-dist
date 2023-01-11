<?php

declare (strict_types=1);
namespace PoP\ComponentModel\AttachableExtensions;

interface AttachExtensionServiceInterface
{
    /**
     * @param string $event
     * @param string $group
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionInterface $extension
     */
    public function enqueueExtension($event, $group, $extension) : void;
    /**
     * @param string $event
     */
    public function attachExtensions($event) : void;
}
