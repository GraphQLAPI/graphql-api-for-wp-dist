<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\TypeResolvers;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
abstract class AbstractCustomPostTypeResolver extends AbstractTypeResolver
{
    public function getSchemaTypeDescription() : ?string
    {
        return $this->translationAPI->__('Representation of a custom post', 'customposts');
    }
    /**
     * @return string|int|null
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getID($resultItem);
    }
}
