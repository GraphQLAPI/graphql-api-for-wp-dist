<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostCategoryMutations\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
trait SetCategoriesOnCustomPostFieldResolverTrait
{
    protected abstract function getTypeResolverClass() : string;
    protected abstract function getCategoryTypeResolverClass() : string;
    protected abstract function getTypeMutationResolverClass() : string;
    protected function getEntityName() : string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return $translationAPI->__('custom post', 'custompost-category-mutations');
    }
}
