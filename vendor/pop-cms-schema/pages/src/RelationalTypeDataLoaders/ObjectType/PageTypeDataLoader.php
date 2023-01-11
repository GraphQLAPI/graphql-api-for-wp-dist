<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\RelationalTypeDataLoaders\ObjectType;

use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\AbstractCustomPostTypeDataLoader;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
class PageTypeDataLoader extends AbstractCustomPostTypeDataLoader
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
     * @return mixed[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function executeQuery($query, $options = []) : array
    {
        return $this->getPageTypeAPI()->getPages($query, $options);
    }
}
