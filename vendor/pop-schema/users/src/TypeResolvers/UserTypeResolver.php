<?php

declare (strict_types=1);
namespace PoPSchema\Users\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\TypeDataLoaders\UserTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
class UserTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    public const NAME = 'User';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a user', 'users');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $cmsusersresolver = \PoPSchema\Users\ObjectPropertyResolverFactory::getInstance();
        $user = $resultItem;
        return $cmsusersresolver->getUserId($user);
    }
    public function getTypeDataLoaderClass() : string
    {
        return \PoPSchema\Users\TypeDataLoaders\UserTypeDataLoader::class;
    }
}
