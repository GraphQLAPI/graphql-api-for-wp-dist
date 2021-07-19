<?php

declare (strict_types=1);
namespace PoPSchema\Posts\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
abstract class AbstractPostTypeResolverPicker extends AbstractTypeResolverPicker
{
    public function getTypeResolverClass() : string
    {
        return PostTypeResolver::class;
    }
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->isInstanceOfPostType($object);
    }
    /**
     * @param string|int $resultItemID
     */
    public function isIDOfType($resultItemID) : bool
    {
        $postTypeAPI = PostTypeAPIFacade::getInstance();
        return $postTypeAPI->postExists($resultItemID);
    }
}
