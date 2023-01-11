<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\TypeResolvers\ObjectType;

use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType\PageTypeDataLoader;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
class PageObjectTypeResolver extends AbstractCustomPostObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType\PageTypeDataLoader|null
     */
    private $pageTypeDataLoader;
    /**
     * @var \PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface|null
     */
    private $pageTypeAPI;
    /**
     * @param \PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType\PageTypeDataLoader $pageTypeDataLoader
     */
    public final function setPageTypeDataLoader($pageTypeDataLoader) : void
    {
        $this->pageTypeDataLoader = $pageTypeDataLoader;
    }
    protected final function getPageTypeDataLoader() : PageTypeDataLoader
    {
        /** @var PageTypeDataLoader */
        return $this->pageTypeDataLoader = $this->pageTypeDataLoader ?? $this->instanceManager->getInstance(PageTypeDataLoader::class);
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
    public function getTypeName() : string
    {
        return 'Page';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Representation of a page', 'pages');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        $page = $object;
        return $this->getPageTypeAPI()->getPageID($page);
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getPageTypeDataLoader();
    }
}
