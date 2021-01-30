<?php

declare (strict_types=1);
namespace PoPSchema\GenericCustomPosts\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver;
use PoPSchema\GenericCustomPosts\TypeDataLoaders\GenericCustomPostTypeDataLoader;
class GenericCustomPostTypeResolver extends \PoPSchema\CustomPosts\TypeResolvers\AbstractCustomPostTypeResolver
{
    public const NAME = 'GenericCustomPost';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Any custom post, with or without its own type for the schema', 'customposts');
    }
    public function getTypeDataLoaderClass() : string
    {
        return \PoPSchema\GenericCustomPosts\TypeDataLoaders\GenericCustomPostTypeDataLoader::class;
    }
}
