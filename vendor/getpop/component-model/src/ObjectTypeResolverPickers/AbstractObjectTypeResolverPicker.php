<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface;
use PoP\ComponentModel\AttachableExtensions\AttachableExtensionTrait;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractObjectTypeResolverPicker implements \PoP\ComponentModel\ObjectTypeResolverPickers\ObjectTypeResolverPickerInterface
{
    use AttachableExtensionTrait;
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface|null
     */
    private $attachableExtensionManager;
    /**
     * @param \PoP\ComponentModel\AttachableExtensions\AttachableExtensionManagerInterface $attachableExtensionManager
     */
    public final function setAttachableExtensionManager($attachableExtensionManager) : void
    {
        $this->attachableExtensionManager = $attachableExtensionManager;
    }
    protected final function getAttachableExtensionManager() : AttachableExtensionManagerInterface
    {
        /** @var AttachableExtensionManagerInterface */
        return $this->attachableExtensionManager = $this->attachableExtensionManager ?? $this->instanceManager->getInstance(AttachableExtensionManagerInterface::class);
    }
    /**
     * @return string[]
     */
    public final function getClassesToAttachTo() : array
    {
        return $this->getUnionTypeResolverClassesToAttachTo();
    }
}
