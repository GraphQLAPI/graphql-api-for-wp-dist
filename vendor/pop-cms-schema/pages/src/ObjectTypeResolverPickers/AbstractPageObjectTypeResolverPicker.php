<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\ObjectTypeResolverPickers;

use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
abstract class AbstractPageObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver|null
     */
    private $pageObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface|null
     */
    private $pageTypeAPI;
    /**
     * @param \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver $pageObjectTypeResolver
     */
    public final function setPageObjectTypeResolver($pageObjectTypeResolver) : void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    protected final function getPageObjectTypeResolver() : PageObjectTypeResolver
    {
        /** @var PageObjectTypeResolver */
        return $this->pageObjectTypeResolver = $this->pageObjectTypeResolver ?? $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface $pageTypeAPI
     */
    public final function setPageTypeAPI($pageTypeAPI) : void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    protected final function getPageTypeAPI() : PageTypeAPIInterface
    {
        /** @var PageTypeAPIInterface */
        return $this->pageTypeAPI = $this->pageTypeAPI ?? $this->instanceManager->getInstance(PageTypeAPIInterface::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getPageObjectTypeResolver();
    }
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool
    {
        return $this->getPageTypeAPI()->isInstanceOfPageType($object);
    }
    /**
     * @param string|int $objectID
     */
    public function isIDOfType($objectID) : bool
    {
        return $this->getPageTypeAPI()->pageExists($objectID);
    }
}
