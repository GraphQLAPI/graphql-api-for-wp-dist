<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostTagMutations\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
trait SetTagsOnCustomPostFieldResolverTrait
{
    protected abstract function getTypeResolverClass() : string;
    protected abstract function getTypeMutationResolverClass() : string;
    protected function getEntityName() : string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('custom post', 'custompost-tag-mutations');
    }
}
