<?php

declare (strict_types=1);
namespace PoPSchema\PostTags\FieldResolvers;

use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Tags\FieldResolvers\AbstractCustomPostQueryableFieldResolver;
use PoPSchema\PostTags\ComponentContracts\PostTagAPISatisfiedContractTrait;
class PostQueryableFieldResolver extends \PoPSchema\Tags\FieldResolvers\AbstractCustomPostQueryableFieldResolver
{
    use PostTagAPISatisfiedContractTrait;
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\Posts\TypeResolvers\PostTypeResolver::class];
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['tags' => $translationAPI->__('Tags added to this post', 'pop-post-tags'), 'tagCount' => $translationAPI->__('Number of tags added to this post', 'pop-post-tags')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
}
