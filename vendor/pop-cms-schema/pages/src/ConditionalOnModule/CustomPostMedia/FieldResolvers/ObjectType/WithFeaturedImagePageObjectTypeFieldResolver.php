<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\ConditionalOnModule\CustomPostMedia\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType\AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class WithFeaturedImagePageObjectTypeFieldResolver extends AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface|null
     */
    private $pageTypeAPI;
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
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [PageObjectTypeResolver::class];
    }
    protected function getCustomPostType() : string
    {
        return $this->getPageTypeAPI()->getPageCustomPostType();
    }
}
