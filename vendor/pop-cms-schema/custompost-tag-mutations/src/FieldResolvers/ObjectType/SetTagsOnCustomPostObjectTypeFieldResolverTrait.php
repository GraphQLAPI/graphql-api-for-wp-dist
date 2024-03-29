<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostTagMutations\FieldResolvers\ObjectType;

use PoP\Root\Translation\TranslationAPIInterface;
trait SetTagsOnCustomPostObjectTypeFieldResolverTrait
{
    protected abstract function getTranslationAPI() : TranslationAPIInterface;
    protected function getEntityName() : string
    {
        return $this->getTranslationAPI()->__('custom post', 'custompost-tag-mutations');
    }
}
