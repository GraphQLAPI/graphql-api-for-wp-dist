<?php

declare (strict_types=1);
namespace PoPSchema\Posts\TypeDataLoaders;

use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader;
class PostTypeDataLoader extends \PoPSchema\CustomPosts\TypeDataLoaders\AbstractCustomPostTypeDataLoader
{
    public function getObjects(array $ids) : array
    {
        $postTypeAPI = \PoPSchema\Posts\Facades\PostTypeAPIFacade::getInstance();
        $query = $this->getObjectQuery($ids);
        return $postTypeAPI->getPosts($query);
    }
    public function executeQuery($query, array $options = [])
    {
        $postTypeAPI = \PoPSchema\Posts\Facades\PostTypeAPIFacade::getInstance();
        return $postTypeAPI->getPosts($query, $options);
    }
}
