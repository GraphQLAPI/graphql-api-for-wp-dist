<?php

declare (strict_types=1);
namespace PoPSchema\PostMutations\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPSchema\CustomPostMutations\FieldResolvers\AbstractCustomPostFieldResolver;
class PostFieldResolver extends \PoPSchema\CustomPostMutations\FieldResolvers\AbstractCustomPostFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\Posts\TypeResolvers\PostTypeResolver::class);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['update' => $translationAPI->__('Update the post', 'post-mutations')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function resolveFieldMutationResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'update':
                return \PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver::class;
        }
        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'update':
                return \PoPSchema\Posts\TypeResolvers\PostTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
