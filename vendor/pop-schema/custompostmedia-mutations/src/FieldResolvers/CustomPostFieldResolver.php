<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMediaMutations\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
class CustomPostFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['setFeaturedImage', 'removeFeaturedImage'];
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['setFeaturedImage' => $translationAPI->__('Set the featured image on the custom post', 'custompostmedia-mutations'), 'removeFeaturedImage' => $translationAPI->__('Remove the featured image on the custom post', 'custompostmedia-mutations')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['setFeaturedImage' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'removeFeaturedImage' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['setFeaturedImage', 'removeFeaturedImage'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'setFeaturedImage':
                return [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::MEDIA_ITEM_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('The ID of the featured image, of type \'%s\'', 'custompostmedia-mutations'), \PoPSchema\Media\TypeResolvers\MediaTypeResolver::NAME), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]];
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }
    /**
     * Validated the mutation on the resultItem because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return \true;
        }
        return parent::validateMutationOnResultItem($typeResolver, $fieldName);
    }
    /**
     * @param object $resultItem
     */
    protected function getFieldArgsToExecuteMutation(array $fieldArgs, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName) : array
    {
        $fieldArgs = parent::getFieldArgsToExecuteMutation($fieldArgs, $typeResolver, $resultItem, $fieldName);
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                $fieldArgs[\PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID] = $typeResolver->getID($customPost);
                break;
        }
        return $fieldArgs;
    }
    public function resolveFieldMutationResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
                return \PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver::class;
            case 'removeFeaturedImage':
                return \PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver::class;
        }
        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return \PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
