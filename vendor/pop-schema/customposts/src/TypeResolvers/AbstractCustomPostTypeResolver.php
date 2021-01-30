<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
abstract class AbstractCustomPostTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a custom post', 'customposts');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getID($resultItem);
    }
}
