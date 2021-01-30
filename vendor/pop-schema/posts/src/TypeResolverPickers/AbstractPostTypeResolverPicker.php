<?php

declare (strict_types=1);
namespace PoPSchema\Posts\TypeResolverPickers;

use PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker;
use PoPSchema\Posts\Facades\PostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
abstract class AbstractPostTypeResolverPicker extends \PoP\ComponentModel\TypeResolverPickers\AbstractTypeResolverPicker
{
    public function getTypeResolverClass() : string
    {
        return \PoPSchema\Posts\TypeResolvers\PostTypeResolver::class;
    }
    public function isInstanceOfType($object) : bool
    {
        $postTypeAPI = \PoPSchema\Posts\Facades\PostTypeAPIFacade::getInstance();
        return $postTypeAPI->isInstanceOfPostType($object);
    }
    public function isIDOfType($resultItemID) : bool
    {
        $postTypeAPI = \PoPSchema\Posts\Facades\PostTypeAPIFacade::getInstance();
        return $postTypeAPI->postExists($resultItemID);
    }
}
