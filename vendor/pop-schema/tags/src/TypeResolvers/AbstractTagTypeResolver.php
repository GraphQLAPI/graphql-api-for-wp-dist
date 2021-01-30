<?php

declare (strict_types=1);
namespace PoPSchema\Tags\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;
abstract class AbstractTagTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    use TagAPIRequestedContractTrait;
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Representation of a tag, added to a custo post', 'tags');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $cmstagsresolver = $this->getObjectPropertyAPI();
        $tag = $resultItem;
        return $cmstagsresolver->getTagID($tag);
    }
}
