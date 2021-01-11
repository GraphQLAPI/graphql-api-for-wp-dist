<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;

abstract class AbstractCustomPostTypeResolver extends AbstractTypeResolver
{
    public function getSchemaTypeDescription(): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a custom post', 'customposts');
    }

    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getID($resultItem);
    }
}
