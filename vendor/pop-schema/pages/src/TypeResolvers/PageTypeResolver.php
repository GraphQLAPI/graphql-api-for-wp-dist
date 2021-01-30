<?php

declare (strict_types=1);
namespace PoPSchema\Pages\TypeResolvers;

use PoPSchema\Pages\TypeDataLoaders\PageTypeDataLoader;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;
class PageTypeResolver extends \PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver
{
    public const NAME = 'Page';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a page', 'pages');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $cmspagesresolver = \PoPSchema\Pages\ObjectPropertyResolverFactory::getInstance();
        $page = $resultItem;
        return $cmspagesresolver->getPageId($page);
    }
    public function getTypeDataLoaderClass() : string
    {
        return \PoPSchema\Pages\TypeDataLoaders\PageTypeDataLoader::class;
    }
}
