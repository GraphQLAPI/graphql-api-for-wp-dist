<?php

declare (strict_types=1);
namespace PoPSchema\Media\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Media\TypeDataLoaders\MediaTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
class MediaTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    public const NAME = 'Media';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Media elements (such as images, videos, etc), attached to a post or independent', 'media');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $cmsmediaresolver = \PoPSchema\Media\ObjectPropertyResolverFactory::getInstance();
        $media = $resultItem;
        return $cmsmediaresolver->getMediaId($media);
    }
    public function getTypeDataLoaderClass() : string
    {
        return \PoPSchema\Media\TypeDataLoaders\MediaTypeDataLoader::class;
    }
}
